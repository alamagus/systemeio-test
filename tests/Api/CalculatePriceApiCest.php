<?php

namespace App\Tests\Api;

use App\Tests\Support\ApiTester;
use Codeception\Module\Symfony;
use Codeception\Util\HttpCode;

class CalculatePriceApiCest
{
    public function _before(Symfony $I)
    {
        // Any setup needed before each test
    }

    public function testCalculatePriceWithoutTaxOrCoupon(ApiTester $I)
    {
        $I->wantTo('calculate price without tax or coupon');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 1,
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'price' => '100.00',
        ]);
    }

    public function testCalculatePriceWithGermanTax(ApiTester $I)
    {
        $I->wantTo('calculate price with German tax');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 1,
            'taxNumber' => 'DE276452187',
            'couponCode' => null
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'price' => '119.00',
        ]);
    }

    public function testCalculatePriceWithPercentageCoupon(ApiTester $I)
    {
        $I->wantTo('calculate price with percentage coupon');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 1,
            'taxNumber' => null,
            'couponCode' => 'P10'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'price' => '90.00',
        ]);
    }

    public function testCalculatePriceWithFixedAmountCoupon(ApiTester $I)
    {
        $I->wantTo('calculate price with fixed amount coupon');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 1,
            'taxNumber' => null,
            'couponCode' => 'F10'
        ]);
        
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'price' => '90.00',
        ]);
    }

    public function testCalculatePriceWithInvalidTaxNumber(ApiTester $I)
    {
        $I->wantTo('fail when calculating price with invalid tax number');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 1,
            'taxNumber' => 'INVALID123',
            'couponCode' => null
        ]);
        
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"error"');
    }

    public function testCalculatePriceWithNonExistentProduct(ApiTester $I)
    {
        $I->wantTo('fail when calculating price with non-existent product');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'product' => 999,
            'taxNumber' => null,
            'couponCode' => null
        ]);
        
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"error"');
    }

    public function testCalculatePriceWithMissingProduct(ApiTester $I)
    {
        $I->wantTo('fail when calculating price with missing product');
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/calculate-price', [
            'taxNumber' => null,
            'couponCode' => null
        ]);
        
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"error"');
    }
}