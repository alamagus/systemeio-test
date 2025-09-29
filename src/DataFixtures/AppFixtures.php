<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['name' => 'Iphone', 'price' => 10000],      // 100 euros in cents
            ['name' => 'Наушники', 'price' => 2000],     // 20 euros in cents
            ['name' => 'Чехол', 'price' => 1000],        // 10 euros in cents
        ];

        foreach ($products as $productData) {
            $existingProduct = $manager->getRepository(Product::class)
                ->findOneBy(['name' => $productData['name']]);

            if (!$existingProduct) {
                $product = new Product();
                $product->setName($productData['name']);
                $product->setPrice($productData['price']);
                $manager->persist($product);
            }
        }

        // Create sample coupons
        $coupons = [
            ['code' => 'P10', 'type' => CouponType::PERCENTAGE, 'value' => 10],   // 10% discount
            ['code' => 'P15', 'type' => CouponType::PERCENTAGE, 'value' => 15],   // 15% discount
            ['code' => 'F10', 'type' => CouponType::FIXED_AMOUNT, 'value' => 1000], // 10 euro discount in cents
            ['code' => 'F15', 'type' => CouponType::FIXED_AMOUNT, 'value' => 1500], // 15 euro discount in cents
        ];

        foreach ($coupons as $couponData) {
            $existingCoupon = $manager->getRepository(Coupon::class)
                ->findOneBy(['code' => $couponData['code']]);

            if (!$existingCoupon) {
                $coupon = new Coupon();
                $coupon->setCode($couponData['code']);
                $coupon->setType($couponData['type']);
                $coupon->setValue($couponData['value']);
                $manager->persist($coupon);
            }
        }

        $manager->flush();
    }
}
