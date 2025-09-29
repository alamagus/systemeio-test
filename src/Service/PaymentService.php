<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Interface\PaymentProcessorInterface;
use Exception;

class PaymentService
{
    /**
     * @var PaymentProcessorInterface[]
     */
    private array $processors;

    public function __construct(
        PaymentProcessorInterface ...$processors
    ) {
        foreach ($processors as $processor) {
            $this->processors[$processor->getName()] = $processor;
        }
    }

    public function processPayment(int $amount, string $processorName): bool
    {
        if (!isset($this->processors[$processorName])) {
            throw new Exception('Invalid payment processor name');
        }

        return $this->processors[$processorName]->processPayment($amount);
    }

    /**
     * @return string[]
     */
    public function getAvailableProcessors(): array
    {
        return array_keys($this->processors);
    }
}