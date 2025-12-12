<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Interface\PaymentProcessorInterface;
use Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

readonly class PaymentService
{
    public function __construct(
        #[AutowireLocator(PaymentProcessorInterface::class, defaultIndexMethod: 'getName')]
        private ContainerInterface $processors,
    ) {
    }

    public function processPayment(int $amount, string $processorName): bool
    {
        if (!$this->processors->has($processorName)) {
            throw new Exception('Invalid payment processor name');
        }

        return $this->processors->get($processorName)->processPayment($amount);
    }
}