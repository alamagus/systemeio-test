<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    //with introduction of Property hooks, there's no need in boilerplate setters and getters anymore
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column]
    public int $id;

    #[ORM\Column(length: 255)]
    public string $name;

    #[ORM\Column]
    public ?int $price = null;
}
