<?php

declare(strict_types=1);

namespace App\Service\Adapter;

use App\Service\Interface\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private StripePaymentProcessor $stripeProcessor
    ) {
    }

    public function processPayment(int $amount): bool
    {
        try {
            // Stripe expects the amount as a float
            return $this->stripeProcessor->processPayment((int)round($amount / 100, 2));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getName(): string
    {
        return 'stripe';
    }
}