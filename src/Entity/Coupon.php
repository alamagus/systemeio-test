<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\CouponType;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 255, unique: true)]
    public string $code;

    #[ORM\Column(enumType: CouponType::class)]
    public CouponType $type;

    #[ORM\Column]
    public int $value;
}
