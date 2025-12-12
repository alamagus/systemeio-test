<?php

declare(strict_types=1);

namespace App\Helper;

use BcMath\Number;

class VatHelper
{
    private const string GERMANY = 'DE';
    private const string ITALY = 'IT';
    private const string FRANCE = 'FR';
    private const string GREECE = 'GR';

    private const array COUNTRY_VATIN_PATTERNS = [
        self::GERMANY => '/^DE[0-9]{9}$/',
        self::ITALY => '/^IT[0-9]{11}$/',
        self::FRANCE => '/^FR[A-Z]{2}[0-9]{9}$/',
        self::GREECE => '/^GR[0-9]{9}$/',
    ];

    private const array TAX_RATES = [
        self::GERMANY => '0.19', // 19% for Germany
        self::ITALY => '0.22', // 22% for Italy
        self::FRANCE => '0.20', // 20% for France
        self::GREECE => '0.24', // 24% for Greece
    ];

    public static function getCountryCode(string $vatNumber): ?string
    {
        return array_find_key(self::COUNTRY_VATIN_PATTERNS, fn($pattern) => preg_match($pattern, $vatNumber));
    }

    public static function getVatRate(string $vatNumber): Number
    {
        $countryCode = self::getCountryCode($vatNumber);
        $vatRate = $countryCode ? self::TAX_RATES[$countryCode] ?? 0 : 0;

        return new Number($vatRate);
    }
}