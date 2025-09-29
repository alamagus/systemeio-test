<?php

declare(strict_types=1);

namespace App\Enum;

enum CouponType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED_AMOUNT = 'fixed_amount';
}