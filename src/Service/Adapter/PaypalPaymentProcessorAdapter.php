<?php

declare(strict_types=1);

namespace App\Service\Adapter;

use App\Service\Interface\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $paypalProcessor
    ) {
    }

    public function processPayment(int $amount): bool
    {
        try {
            $this->paypalProcessor->pay($amount);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getName(): string
    {
        return 'paypal';
    }
}