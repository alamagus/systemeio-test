<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Enum\CouponType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //disable autoincrement of ID, otherwise it's quite inconvenient to test
        $metadata = $manager->getClassMetaData(Product::class);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $products = [
            ['id' => 1, 'name' => 'Iphone', 'price' => 10000],      // 100 euros in cents
            ['id' => 2, 'name' => 'Наушники', 'price' => 2000],     // 20 euros in cents
            ['id' => 3, 'name' => 'Чехол', 'price' => 1000],        // 10 euros in cents
        ];

        foreach ($products as $productData) {
            $existingProduct = $manager->getRepository(Product::class)
                ->find($productData['id']);

            if (!$existingProduct) {
                $product = new Product();
                $product->id = $productData['id'];
                $product->name = $productData['name'];
                $product->price = $productData['price'];
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
                $coupon->code = $couponData['code'];
                $coupon->type = $couponData['type'];
                $coupon->value = $couponData['value'];
                $manager->persist($coupon);
            }
        }

        $manager->flush();
    }
}
