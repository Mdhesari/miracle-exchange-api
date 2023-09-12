<?php

namespace App\Enums;

enum UserStatus
{
    case Accepted;
    case Rejected;
    case Pending;
    case AdminPending;
}
