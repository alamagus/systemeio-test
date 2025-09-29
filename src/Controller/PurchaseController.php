<?php

namespace App\Controller;

use App\Dto\PurchaseRequest;
use App\Service\PurchaseProcessingService;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Purchase Processor")]
#[Route('/api')]
class PurchaseController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    #[OA\Post(
        summary: 'Process a purchase with payment',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new Property(property: 'product', type: 'integer', example: 1),
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
                        new Property(property: 'price', type: 'number', format: 'float', example: 122.0),
                        new Property(property: 'currency', type: 'string', example: 'EUR')
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
    public function purchase(#[MapRequestPayload] PurchaseRequest $dto): JsonResponse
    {


        return $this->json($dto);
    }
}