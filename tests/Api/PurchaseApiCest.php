<?php

namespace App\Tests\Api;

use App\Tests\Support\ApiTester;
use Codeception\Module\Symfony;
use Codeception\Util\HttpCode;

class PurchaseApiCest
{
    public function _before(Symfony $I)
    {
        // Any setup needed before each test
    }

    public function testSuccessfulPurchaseWithPaypal(ApiTester $I)
    {
        $I->wantTo('successfully process a purchase with PayPal');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/purchase', [
            'product' => 1,
            'taxNumber' => 'IT01234567890',
            'couponCode' => null,
            'paymentProcessor' => 'paypal'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => 'Purchase successful',
            'price' => 122.0,
            'currency' => 'EUR'
        ]);
    }

    public function testSuccessfulPurchaseWithStripe(ApiTester $I)
    {
        $I->wantTo('successfully process a purchase with Stripe');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/purchase', [
            'product' => 2, // Higher value product for Stripe minimum
            'taxNumber' => 'FRXX123456789',
            'couponCode' => null,
            'paymentProcessor' => 'stripe'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => 'Purchase successful',
            'price' => 24.0, // 20 + 20% tax
            'currency' => 'EUR'
        ]);
    }

    public function testFailedPurchaseWithInvalidPaymentProcessor(ApiTester $I)
    {
        $I->wantTo('fail when processing a purchase with invalid payment processor');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/purchase', [
            'product' => 1,
            'taxNumber' => 'DE276452187',
            'couponCode' => null,
            'paymentProcessor' => 'invalid_processor'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"errors"');
    }

    public function testFailedPurchaseWithInvalidTaxNumber(ApiTester $I)
    {
        $I->wantTo('fail when processing a purchase with invalid tax number');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/purchase', [
            'product' => 1,
            'taxNumber' => 'INVALID123',
            'couponCode' => null,
            'paymentProcessor' => 'paypal'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"errors"');
    }

    public function testFailedPurchaseWithoutRequiredFields(ApiTester $I)
    {
        $I->wantTo('fail when processing a purchase without required fields');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/purchase', [
            'taxNumber' => null,
            'couponCode' => null,
            'paymentProcessor' => 'paypal'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"errors"');
    }
}