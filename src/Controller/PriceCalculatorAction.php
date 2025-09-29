<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CalculatePriceRequest;
use App\Service\Interface\PriceCalculatorInterface;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "Price Calculator")]
#[Route('/api')]
class PriceCalculatorAction extends AbstractController
{
    public function __construct(
        private PriceCalculatorInterface $priceCalculator
    ) {
    }

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    #[OA\Post(
        summary: 'Calculate the final price of a product including tax and discounts',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new Property(property: 'product', type: 'integer', example: 1),
                        new Property(property: 'taxNumber', type: 'string', example: 'DE276452187'),
                        new Property(property: 'couponCode', type: 'string', example: 'P15')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully calculated price',
                content: new OA\JsonContent(
                    properties: [
                        new Property(property: 'price', type: 'number', format: 'float', example: '101.15'),
                        new Property(property: 'currency', type: 'string', example: 'EUR')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new Property(property: 'errors', type: 'object')
                    ]
                )
            )
        ]
    )]
    public function calculatePrice(#[MapRequestPayload] CalculatePriceRequest $dto): JsonResponse
    {
        $price = $this->priceCalculator->calculatePrice($dto->product, $dto->taxNumber, $dto->couponCode);

        $price = number_format($price / 100, 2); //convert from minor to major units
        return $this->json((array) $dto + ['price' => $price]);
    }
}