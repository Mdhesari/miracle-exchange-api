<?php

namespace Modules\User\Enums;

enum UserStatus
{
    case Accepted;
    case Rejected;
    case Pending;
    case AdminPending;
}
