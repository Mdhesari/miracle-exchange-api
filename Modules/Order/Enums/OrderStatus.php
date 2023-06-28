<?php

namespace Modules\Order\Enums;

enum OrderStatus
{
    case Done;
    case Pending;
    case FillPending;
    case Rejected;
}
