<?php

namespace App\Dto;

use App\Entity\Coupon;
use App\Entity\Product;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceRequest
{
    #[Assert\Type(type: 'integer')]
    #[Assert\Positive]
    #[EntityExist(entity: Product::class, property: 'id')]
    public ?int $product = null;

    #[Assert\Type(type: 'string')]
    #[Assert\NotBlank]
    public ?string $taxNumber = null;

    #[Assert\Type(type: 'string')]
    #[EntityExist(entity: Coupon::class, property: 'code')]
    public ?string $couponCode = null;
}