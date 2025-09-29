<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest extends CalculatePriceRequest
{
    #[Assert\Type(type: 'string')]
    #[Assert\Choice(choices: ['paypal', 'stripe'])]
    public ?string $paymentProcessor = null;
}