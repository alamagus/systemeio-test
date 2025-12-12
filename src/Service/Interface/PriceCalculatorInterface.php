<?php

declare(strict_types=1);

namespace App\Service\Interface;

interface PriceCalculatorInterface
{
    /**
     * Calculate final price of a product, applying VAT tax and coupon discount
     *
     * @param int $productId
     * @param string|null $vatNumber VAT identification number
     * @param string|null $couponCode
     * @return int Final price in currency minor units
     */
    public function calculatePrice(int $productId, ?string $vatNumber, ?string $couponCode = null): int;
}