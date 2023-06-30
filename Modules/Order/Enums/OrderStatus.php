<?php

namespace Modules\Order\Enums;

enum OrderStatus
{
    case Done;
    case Pending;
    case AdminPending;
    case FillPending;
    case Rejected;
}
