<?php

namespace App\Enums;

enum DeliveryStatusEnum: int
{
    case NEW = 1;
    case OUT_FOR_DELIVERY = 2;
    case DELIVERED = 3;
    case RETURNED = 4;
    case CANCELLED = 5;
    case FAILED = 6;
}
