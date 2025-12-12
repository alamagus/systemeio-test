<?php

declare(strict_types=1);

namespace App\Service\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface PaymentProcessorInterface
{
    public function processPayment(int $amount): bool;

    public static function getName(): string;
}
