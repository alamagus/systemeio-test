<?php

declare(strict_types=1);

namespace App\Service\Interface;

interface PaymentProcessorInterface
{
    public function processPayment(int $amount): bool;
    public function getName(): string;
}