<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\PurchaseRequest;
use App\Service\Interface\PriceCalculatorInterface;
use App\Service\PaymentService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Purchase Processor")]
#[Route('/api')]
class PurchaseAction extends AbstractController
{
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    #[OA\Post(
        summary: 'Process a purchase with payment',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new Property(property: 'productId', type: 'integer', example: 1),
                        new Property(property: 'taxNumber', type: 'string', example: 'IT12345678900'),
                        new Property(property: 'couponCode', type: 'string', example: 'P15'),
                        new Property(property: 'paymentProcessor', type: 'string', example: 'paypal')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Purchase successful',
                content: new OA\JsonContent(
                    properties: [
                        new Property(property: 'message', type: 'string', example: 'Purchase successful'),
                        new Property(property: 'price', type: 'number', format: 'decimal', example: '122.00'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error or payment failed',
                content: new OA\JsonContent(
                    properties: [
                        new Property(property: 'errors', type: 'object')
                    ]
                )
            )
        ]
    )]
    public function __invoke(
        #[MapRequestPayload] PurchaseRequest $dto,
        PriceCalculatorInterface $priceCalculator,
        PaymentService $paymentService,
    ): JsonResponse
    {
        $price = $priceCalculator->calculatePrice($dto->productId, $dto->taxNumber, $dto->couponCode);
        $paymentStatus = $paymentService->processPayment($price, $dto->paymentProcessor);
        $price = number_format($price / 100, 2); //convert from minor to major units
        return $this->json(['message' => $paymentStatus ? 'Purchase successful' : 'Purchase failed', 'price' => $price]);
    }
}