<?php

namespace App\Domain\Reconciliation\Services;

use App\Domain\Banking\Models\BankTransaction;
use App\Domain\ERP\Models\ErpTitle;
use App\Domain\Events\Services\OutboxService;
use App\Domain\Reconciliation\Models\ReconciliationCandidate;
use App\Domain\Reconciliation\Models\ReconciliationCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final readonly class ReconciliationEngine
{
    public function __construct(private OutboxService $outbox) {}

    public function evaluate(BankTransaction $transaction, ?string $correlationId = null): ReconciliationCase
    {
        $case = ReconciliationCase::query()->withoutGlobalScopes()->firstOrCreate(
            ['bank_transaction_id' => $transaction->getKey()],
            [
                'organization_id' => $transaction->organization_id,
                'company_id' => $transaction->company_id,
                'status' => 'open',
                'version' => 1,
            ],
        );

        if ($case->status !== 'open') {
            return $case;
        }

        $titles = ErpTitle::query()->withoutGlobalScopes()
            ->where('organization_id', $transaction->organization_id)
            ->where('company_id', $transaction->company_id)
            ->where('status', 'open')
            ->where('currency', $transaction->currency)
            ->whereIn('type', $transaction->direction === 'credit' ? ['receivable', 'receive'] : ['payable', 'pay'])
            ->where(function (Builder $query) use ($transaction): void {
                $query->whereBetween('open_amount_minor', [max(0, $transaction->amount_minor - 500), $transaction->amount_minor + 500])
                    ->orWhereHas('financialAccount', fn (Builder $account) => $account->where('bank_account_id', $transaction->bank_account_id));
            })
            ->limit(250)
            ->get();

        $candidateIds = [];
        $strictIds = [];
        $best = 0;
        foreach ($titles as $title) {
            [$score, $signals, $strict] = $this->score($transaction, $title);
            if ($score < 25) {
                continue;
            }

            $candidate = ReconciliationCandidate::query()->withoutGlobalScopes()->updateOrCreate(
                ['reconciliation_case_id' => $case->getKey(), 'erp_title_id' => $title->getKey()],
                [
                    'organization_id' => $transaction->organization_id,
                    'score' => $score,
                    'suggested_amount_minor' => min($transaction->amount_minor, $title->open_amount_minor),
                    'signals' => $signals,
                ],
            );
            $candidateIds[] = $candidate->getKey();
            $best = max($best, $score);
            if ($strict) {
                $strictIds[] = $candidate->getKey();
            }
        }

        ReconciliationCandidate::query()->withoutGlobalScopes()
            ->where('reconciliation_case_id', $case->getKey())
            ->when($candidateIds !== [], fn (Builder $query) => $query->whereNotIn('id', $candidateIds))
            ->when($candidateIds === [], fn (Builder $query) => $query)
            ->delete();

        $case->forceFill([
            'best_score' => $best,
            'auto_eligible' => count($strictIds) === 1,
        ])->save();

        $this->outbox->forModel('reconciliation.suggestion.updated', $case, [
            'reconciliation_id' => $case->getKey(),
            'bank_transaction_id' => $transaction->getKey(),
            'candidate_count' => count($candidateIds),
            'best_score' => $best,
            'auto_eligible' => $case->auto_eligible,
            'version' => $case->version,
        ], $correlationId);

        return $case;
    }

    /** @return array{int,array<string,mixed>,bool} */
    private function score(BankTransaction $transaction, ErpTitle $title): array
    {
        $transactionIdentifiers = array_filter(array_map(
            fn (mixed $value): string => Str::lower(trim((string) $value)),
            $transaction->identifiers ?? [],
        ));
        $titleIdentifiers = array_filter(array_map(
            fn (mixed $value): string => Str::lower(trim((string) $value)),
            [...($title->identifiers ?? []), 'external_id' => $title->external_id, 'document_number' => $title->document_number],
        ));
        $exactIdentifiers = array_values(array_intersect($transactionIdentifiers, $titleIdentifiers));
        $amountExact = (int) $transaction->amount_minor === (int) $title->open_amount_minor;
        $mappedBankAccountId = $title->erp_financial_account_id
            ? $title->financialAccount->bank_account_id
            : null;
        $accountCompatible = ! $title->erp_financial_account_id
            || $mappedBankAccountId === null
            || $mappedBankAccountId === $transaction->bank_account_id;
        $accountExact = $mappedBankAccountId === $transaction->bank_account_id;
        $taxId = $transaction->counterparty_tax_id_hash
            && hash_equals((string) $transaction->counterparty_tax_id_hash, (string) $title->counterparty_tax_id_hash);
        $dateDistance = $title->due_at ? abs($title->due_at->diffInDays($transaction->occurred_at, false)) : null;
        $text = $this->textSimilarity($transaction->description, $title->description);

        $signals = [
            'exact_identifier' => ['matched' => $exactIdentifiers !== [], 'values' => $exactIdentifiers],
            'exact_amount' => ['matched' => $amountExact],
            'compatible_account' => ['matched' => $accountCompatible],
            'exact_account_mapping' => ['matched' => $accountExact],
            'counterparty_tax_id' => ['matched' => (bool) $taxId],
            'date_distance_days' => $dateDistance,
            'text_similarity' => $text,
        ];
        $score = min(100,
            ($exactIdentifiers !== [] ? 50 : 0)
            + ($amountExact ? 25 : max(0, 15 - intdiv(abs($transaction->amount_minor - $title->open_amount_minor), 100)))
            + ($accountCompatible ? 10 : 0)
            + ($taxId ? 10 : 0)
            + ($dateDistance !== null && $dateDistance <= 3 ? 3 : 0)
            + ($text >= 0.65 ? 2 : 0),
        );

        // Score nunca basta: automação exige todos os critérios determinísticos e um único candidato.
        $strict = $exactIdentifiers !== [] && $amountExact && $accountExact;

        return [$score, $signals, $strict];
    }

    private function textSimilarity(?string $left, ?string $right): float
    {
        if (! $left || ! $right) {
            return 0.0;
        }

        similar_text(Str::lower($left), Str::lower($right), $percentage);

        return round($percentage / 100, 4);
    }
}
