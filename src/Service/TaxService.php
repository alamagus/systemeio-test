<?php

declare(strict_types=1);

namespace App\Service;

class TaxService
{
    private const GERMANY = 'DE';
    private const ITALY = 'IT';
    private const FRANCE = 'FR';
    private const GREECE = 'GR';

    private const COUNTRY_PATTERNS = [
        self::GERMANY => '/^DE[0-9]{9}$/',
        self::ITALY => '/^IT[0-9]{11}$/',
        self::FRANCE => '/^FR[A-Z]{2}[0-9]{9}$/',
        self::GREECE => '/^GR[0-9]{9}$/',
    ];

    private const TAX_RATES = [
        self::GERMANY => 0.19, // 19% for Germany
        self::ITALY => 0.22, // 22% for Italy
        self::FRANCE => 0.20, // 20% for France
        self::GREECE => 0.24, // 24% for Greece
    ];

    public static function getCountryCode(string $taxNumber): ?string
    {
        foreach (self::COUNTRY_PATTERNS as $country => $pattern) {
            if (preg_match($pattern, $taxNumber)) {
                return $country;
            }
        }

        return null;
    }

    public function getTaxRate(string $taxNumber): float
    {
        $countryCode = self::getCountryCode($taxNumber);
        return $countryCode ? self::TAX_RATES[$countryCode] ?? 0 : 0;
    }
}