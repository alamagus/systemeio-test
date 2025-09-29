<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Validator\Tax;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceRequest
{
    #[Assert\Type(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[EntityExist(entity: Product::class, property: 'id')]
    public ?int $product = null;

    #[Assert\Type(type: 'string')]
    #[Tax]
    public ?string $taxNumber = null;

    #[Assert\Type(type: 'string')]
    #[EntityExist(entity: Coupon::class, property: 'code')]
    public ?string $couponCode = null;
}