<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sabba\OpenFinance\Core\DTO\Money;

class MoneyTest extends TestCase
{
    #[DataProvider('amounts')]
    public function test_it_converts_decimal_without_floats(string $decimal, int $minor): void
    {
        $this->assertSame($minor, Money::fromDecimal($decimal)->minor);
    }

    public static function amounts(): array
    {
        return [['0', 0], ['10.25', 1025], ['-0.01', -1], ['999999999.99', 99999999999]];
    }

    public function test_it_rejects_more_than_two_decimal_places(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Money::fromDecimal('1.001');
    }

    #[DataProvider('minorAmounts')]
    public function test_it_serializes_minor_units_without_floats(int $minor, string $decimal): void
    {
        $this->assertSame($decimal, (new Money($minor))->toDecimal());
    }

    public static function minorAmounts(): array
    {
        return [[0, '0.00'], [1, '0.01'], [-1, '-0.01'], [1025, '10.25'], [99999999999, '999999999.99']];
    }
}
