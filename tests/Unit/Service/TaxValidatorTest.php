<?php

namespace App\Tests\Unit\Service;

use App\Service\TaxService;
use PHPUnit\Framework\TestCase;

class TaxValidatorTest extends TestCase
{
    private TaxService $taxService;

    protected function setUp(): void
    {
        $this->taxService = new TaxService();
    }

    public function testGetCountryCodeWithValidGermanTaxNumber(): void
    {
        $result = $this->taxService->getCountryCode('DE276452187');
        $this->assertEquals('DE', $result);
    }

    public function testGetCountryCodeWithValidItalianTaxNumber(): void
    {
        $result = $this->taxService->getCountryCode('IT01234567890');
        $this->assertEquals('IT', $result);
    }

    public function testGetCountryCodeWithValidFrenchTaxNumber(): void
    {
        $result = $this->taxService->getCountryCode('FRXX123456789');
        $this->assertEquals('FR', $result);
    }

    public function testGetCountryCodeWithValidGreekTaxNumber(): void
    {
        $result = $this->taxService->getCountryCode('GR123456789');
        $this->assertEquals('GR', $result);
    }

    public function testGetCountryCodeWithInvalidTaxNumber(): void
    {
        $result = $this->taxService->getCountryCode('INVALID123');
        $this->assertNull($result);
    }

    public function testGetTaxRateWithValidGermanTaxNumber(): void
    {
        $result = $this->taxService->getTaxRate('DE276452187');
        $this->assertEquals(0.19, $result);
    }

    public function testGetTaxRateWithValidItalianTaxNumber(): void
    {
        $result = $this->taxService->getTaxRate('IT01234567890');
        $this->assertEquals(0.22, $result);
    }

    public function testGetTaxRateWithValidFrenchTaxNumber(): void
    {
        $result = $this->taxService->getTaxRate('FRXX123456789');
        $this->assertEquals(0.20, $result);
    }

    public function testGetTaxRateWithValidGreekTaxNumber(): void
    {
        $result = $this->taxService->getTaxRate('GR123456789');
        $this->assertEquals(0.24, $result);
    }

    public function testGetTaxRateWithInvalidTaxNumber(): void
    {
        $result = $this->taxService->getTaxRate('INVALID123');
        $this->assertEquals(0, $result);
    }
}