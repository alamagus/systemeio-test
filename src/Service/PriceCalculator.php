<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Interface\PriceCalculatorInterface;

class PriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxService $taxService
    ) {
    }

    public function calculatePrice(int $productId, ?string $taxNumber, ?string $couponCode = null): int
    {
        //TODO use pipeline pattern

        /** @var Product $product */
        $product = $this->productRepository->find($productId);

        $resultPrice = $product->getPrice();

        // Apply coupon discount if valid
        $discount = 0;
        if ($couponCode) {
            $coupon = $this->couponRepository->findByCode($couponCode);
            if ($coupon) {
                if ($coupon->getType() === CouponType::PERCENTAGE) {
                    $discount = $resultPrice * ($coupon->getValue() / 100);
                } elseif ($coupon->getType() === CouponType::FIXED_AMOUNT) {
                    $discount = min($coupon->getValue(), $resultPrice); // Fixed discount, can't exceed the price
                }
            }
        }

        $discountedPrice = $resultPrice - $discount;

        // Apply tax if tax number provided
        $taxRate = 0;
        if ($taxNumber) {
            $taxRate = $this->taxService->getTaxRate($taxNumber);
        }

        return (int)round($discountedPrice * (1 + $taxRate));
    }
}