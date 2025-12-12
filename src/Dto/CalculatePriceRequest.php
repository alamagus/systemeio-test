<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Validator\Vat;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CalculatePriceRequest
{
    public function __construct(
        #[Assert\Type(type: 'integer')]
        #[Assert\NotBlank]
        #[Assert\Positive]
        #[EntityExist(entity: Product::class, property: 'id', message: 'Product with id: "%value%" does not exist.')]
        public ?int $product = null,

        #[Assert\Type(type: 'string')]
        #[Vat]
        public ?string $taxNumber = null,

        #[Assert\Type(type: 'string')]
        #[EntityExist(entity: Coupon::class, property: 'code')]
        public ?string $couponCode = null,
    ) {
    }
}
