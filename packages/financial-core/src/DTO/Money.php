<?php

namespace Sabba\OpenFinance\Core\DTO;

use InvalidArgumentException;

final readonly class Money
{
    public function __construct(
        public int $minor,
        public string $currency = 'BRL',
    ) {
        if (preg_match('/^[A-Z]{3}$/', $currency) !== 1) {
            throw new InvalidArgumentException('Currency must be an ISO 4217 code.');
        }
    }

    public static function fromDecimal(string $amount, string $currency = 'BRL'): self
    {
        $normalized = trim(str_replace(',', '.', $amount));

        if (preg_match('/^-?\d+(?:\.\d{1,2})?$/', $normalized) !== 1) {
            throw new InvalidArgumentException('Invalid monetary decimal.');
        }

        $negative = str_starts_with($normalized, '-');
        $unsigned = ltrim($normalized, '-');
        [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');
        $minor = ((int) $whole * 100) + (int) str_pad($fraction, 2, '0');

        return new self($negative ? -$minor : $minor, $currency);
    }

    public function absolute(): self
    {
        return new self(abs($this->minor), $this->currency);
    }

    public function toDecimal(): string
    {
        $negative = $this->minor < 0;
        $digits = ltrim((string) $this->minor, '-');
        $padded = str_pad($digits, 3, '0', STR_PAD_LEFT);

        return ($negative ? '-' : '')
            .substr($padded, 0, -2)
            .'.'
            .substr($padded, -2);
    }
}
