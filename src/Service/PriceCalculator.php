<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Enum\CouponType;
use App\Helper\VatHelper;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Interface\PriceCalculatorInterface;
use BcMath\Number;
use Override;

readonly class PriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
    ) {
    }

    #[Override]
    public function calculatePrice(int $productId, ?string $vatNumber, ?string $couponCode = null): int
    {
        //TODO use moneyphp/money

        /** @var Product $product */
        $product = $this->productRepository->find($productId);

        if (null === $product->price) {
            throw new \RuntimeException("Product[id=$productId]'s price field shouldn't be null");
        }

        $finalPrice = new Number($product->price)
            |> (fn($price) => $this->applyCoupon($couponCode, $price))
            |> (fn($price) => $this->applyTax($vatNumber, $price))
        ;

        return (int)(string)$finalPrice->round();
    }

    public function applyCoupon(?string $couponCode, Number $price): Number
    {
        $discount = 0;
        if (null !== $couponCode) {
            $coupon = $this->couponRepository->findByCode($couponCode);
            if (null !== $coupon) {
                $couponValue = new Number($coupon->value);
                $discount = match($coupon->type) {
                    CouponType::PERCENTAGE => $price * ($couponValue / 100),
                    CouponType::FIXED_AMOUNT => min($couponValue, $price), // Fixed discount, can't exceed the price
                };
            }
        }

        return $price - $discount;
    }

    public function applyTax(?string $vatNumber, Number $price): Number
    {
        $taxRate = 0;
        if (null !== $vatNumber) {
            $taxRate = VatHelper::getVatRate($vatNumber);
        }

        $price *= (1 + $taxRate);

        return $price;
    }
}