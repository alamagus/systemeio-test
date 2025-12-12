<?php

namespace App\Tests\Unit\Service;

use App\Helper\VatHelper;
use BcMath\Number;
use PHPUnit\Framework\TestCase;

class VatHelperTest extends TestCase
{
    public function testGetCountryCodeWithValidGermanTaxNumber(): void
    {
        $result = VatHelper::getCountryCode('DE276452187');
        $this->assertEquals('DE', $result);
    }

    public function testGetCountryCodeWithValidItalianTaxNumber(): void
    {
        $result = VatHelper::getCountryCode('IT01234567890');
        $this->assertEquals('IT', $result);
    }

    public function testGetCountryCodeWithValidFrenchTaxNumber(): void
    {
        $result = VatHelper::getCountryCode('FRXX123456789');
        $this->assertEquals('FR', $result);
    }

    public function testGetCountryCodeWithValidGreekTaxNumber(): void
    {
        $result = VatHelper::getCountryCode('GR123456789');
        $this->assertEquals('GR', $result);
    }

    public function testGetCountryCodeWithInvalidTaxNumber(): void
    {
        $result = VatHelper::getCountryCode('INVALID123');
        $this->assertNull($result);
    }

    public function testGetTaxRateWithValidGermanTaxNumber(): void
    {
        $result = VatHelper::getVatRate('DE276452187');
        $this->assertEquals(new Number('0.19'), $result);
    }

    public function testGetTaxRateWithValidItalianTaxNumber(): void
    {
        $result = VatHelper::getVatRate('IT01234567890');
        $this->assertEquals(new Number('0.22'), $result);
    }

    public function testGetTaxRateWithValidFrenchTaxNumber(): void
    {
        $result = VatHelper::getVatRate('FRXX123456789');
        $this->assertEquals(new Number('0.20'), $result);
    }

    public function testGetTaxRateWithValidGreekTaxNumber(): void
    {
        $result = VatHelper::getVatRate('GR123456789');
        $this->assertEquals(new Number('0.24'), $result);
    }

    public function testGetTaxRateWithInvalidTaxNumber(): void
    {
        $result = VatHelper::getVatRate('INVALID123');
        $this->assertEquals(new Number(0), $result);
    }
}