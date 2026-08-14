<?php

namespace App\Domain\Banking\Services;

use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;

final class BradescoConnectionConfigurator
{
    /**
     * @param  array<string,mixed>  $data
     * @return array{credentials:array<string,mixed>,capabilities:list<string>,certificate_expires_at:CarbonImmutable}
     */
    public function build(array $data): array
    {
        $certificateFile = $data['certificate'] ?? null;
        $privateKeyFile = $data['private_key'] ?? null;
        if (! $certificateFile instanceof UploadedFile || ! $privateKeyFile instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'certificate' => 'Informe o certificado e a chave privada da aplicação.',
            ]);
        }

        $certificate = $this->certificate($certificateFile, 'certificate');
        $chain = isset($data['certificate_chain']) && $data['certificate_chain'] instanceof UploadedFile
            ? $this->certificate($data['certificate_chain'], 'certificate_chain', false)
            : null;
        $privateKeyPassphrase = (string) ($data['private_key_passphrase'] ?? '');
        $privateKeyPem = $this->fileContents($privateKeyFile, 'private_key');
        $privateKey = $this->privateKey($privateKeyPem, $privateKeyPassphrase);

        if (! openssl_x509_check_private_key($certificate['resource'], $privateKey)) {
            throw ValidationException::withMessages([
                'private_key' => 'A chave privada não corresponde ao certificado informado.',
            ]);
        }

        $certificateData = openssl_x509_parse($certificate['resource']);
        if (! is_array($certificateData) || ! isset($certificateData['validTo_time_t'])) {
            throw ValidationException::withMessages([
                'certificate' => 'Não foi possível identificar a validade do certificado.',
            ]);
        }

        $now = now('UTC')->getTimestamp();
        if ((int) $certificateData['validTo_time_t'] <= $now) {
            throw ValidationException::withMessages([
                'certificate' => 'O certificado informado está expirado.',
            ]);
        }
        if (isset($certificateData['validFrom_time_t']) && (int) $certificateData['validFrom_time_t'] > $now) {
            throw ValidationException::withMessages([
                'certificate' => 'O certificado ainda não está válido.',
            ]);
        }
        if ((int) $certificateData['validTo_time_t'] < now('UTC')->addMonthsNoOverflow(2)->getTimestamp()) {
            throw ValidationException::withMessages([
                'certificate' => 'O Bradesco exige certificado A1 com pelo menos 2 meses de validade restante.',
            ]);
        }
        if ((int) $certificateData['validTo_time_t'] > now('UTC')->addYears(3)->addDay()->getTimestamp()) {
            throw ValidationException::withMessages([
                'certificate' => 'O Bradesco aceita certificado A1 com validade máxima de 3 anos.',
            ]);
        }

        $environment = (string) ($data['environment'] ?? '');
        $product = (string) ($data['product'] ?? '');
        $preset = config("openfinance.bradesco.{$product}.environments.{$environment}");
        if (! is_array($preset)) {
            throw ValidationException::withMessages(['environment' => 'Produto ou ambiente Bradesco inválido.']);
        }

        /** @var list<string> $capabilities */
        $capabilities = array_values(array_unique($data['capabilities']));
        $certificatePem = trim($certificate['pem']);
        if ($chain !== null) {
            $certificatePem .= "\n".trim($chain['pem']);
        }

        $productCredentials = [
            'base_url' => $preset['base_url'],
            'token_url' => $preset['token_url'],
            'client_id' => $data['client_id'],
            'client_secret' => $data['client_secret'],
            'grant_type' => 'client_credentials',
            'scope' => '',
            'certificate_pem' => $certificatePem."\n",
            'private_key_pem' => trim($privateKeyPem)."\n",
            'private_key_passphrase' => $privateKeyPassphrase,
        ];
        if ($product === 'pix') {
            $productCredentials = [
                ...$productCredentials,
                'receipts_timeout_seconds' => (int) config('openfinance.bradesco.pix.receipts_timeout_seconds', 45),
                'paths' => [
                    'charge' => '/v2/cob/{txid}',
                    'due_charge' => '/v2/cobv/{txid}',
                    'receipts' => '/v2/pix',
                    'receipt' => '/v2/pix/{endToEndId}',
                    'refund' => '/v2/pix/{endToEndId}/devolucao/{refundId}',
                ],
            ];
        } else {
            $beneficiaryTaxId = preg_replace('/\D+/', '', (string) ($data['beneficiary_tax_id'] ?? '')) ?? '';
            if (! in_array(strlen($beneficiaryTaxId), [11, 14], true)) {
                throw ValidationException::withMessages([
                    'company_id' => 'A empresa deve ter CPF ou CNPJ com 11 ou 14 dígitos para contratar a Cobrança Bradesco.',
                ]);
            }
            $productCredentials = [
                ...$productCredentials,
                'beneficiary_tax_id' => $beneficiaryTaxId,
                'product_code' => $data['wallet_code'],
                'negotiation_number' => $data['negotiation_number'],
                'paths' => [
                    'normal_create' => '/boleto/cobranca-registro/v1/cobranca',
                    'normal_get' => '/boleto/cobranca-consulta/v1/consultar',
                    'normal_update' => '/boleto/cobranca-altera/v1/alterar',
                    'hybrid_create' => '/boleto-hibrido/cobranca-registro/v1/gerarBoleto',
                    'hybrid_get' => '/boleto-hibrido/cobranca-consulta-titulo/v1/consultar',
                    'hybrid_update' => '/boleto-hibrido/cobranca-alteracao/v1/alteraBoletoConsulta',
                    'cancel' => '/boleto/cobranca-baixa/v1/baixar',
                ],
            ];
        }

        return [
            'credentials' => [
                ...($product === 'pix' ? [
                    'default_pix_key' => $data['pix_key'] ?? null,
                    'webhook_secret' => $data['webhook_secret'] ?? null,
                ] : []),
                'products' => [
                    $product => $productCredentials,
                ],
            ],
            'capabilities' => $capabilities,
            'certificate_expires_at' => CarbonImmutable::createFromTimestampUTC((int) $certificateData['validTo_time_t']),
        ];
    }

    /** @return array{pem:string,resource:OpenSSLCertificate} */
    private function certificate(UploadedFile $file, string $field, bool $single = true): array
    {
        $contents = $this->fileContents($file, $field);
        $pem = str_contains($contents, '-----BEGIN CERTIFICATE-----')
            ? trim($contents)
            : "-----BEGIN CERTIFICATE-----\n".chunk_split(base64_encode($contents), 64, "\n").'-----END CERTIFICATE-----';

        preg_match_all('/-----BEGIN CERTIFICATE-----.*?-----END CERTIFICATE-----/s', $pem, $matches);
        $blocks = $matches[0];
        if ($blocks === []) {
            throw ValidationException::withMessages([$field => 'O arquivo não contém um certificado X.509 válido.']);
        }

        foreach ($blocks as $block) {
            if (@openssl_x509_read($block) === false) {
                throw ValidationException::withMessages([$field => 'O arquivo não contém um certificado X.509 válido.']);
            }
        }

        $resource = @openssl_x509_read($blocks[0]);
        if ($resource === false) {
            throw ValidationException::withMessages([$field => 'O arquivo não contém um certificado X.509 válido.']);
        }

        return [
            'pem' => $single ? $blocks[0] : implode("\n", $blocks),
            'resource' => $resource,
        ];
    }

    private function privateKey(string $contents, string $passphrase): OpenSSLAsymmetricKey
    {
        if (! str_contains($contents, 'PRIVATE KEY-----')) {
            throw ValidationException::withMessages([
                'private_key' => 'A chave privada deve estar no formato PEM.',
            ]);
        }

        $key = @openssl_pkey_get_private($contents, $passphrase);
        if ($key === false) {
            throw ValidationException::withMessages([
                'private_key' => 'A chave privada ou a frase secreta informada é inválida.',
            ]);
        }

        return $key;
    }

    private function fileContents(UploadedFile $file, string $field): string
    {
        $contents = $file->get();
        if ($contents === false) {
            throw ValidationException::withMessages([
                $field => 'Não foi possível ler o arquivo enviado.',
            ]);
        }

        return $contents;
    }
}
