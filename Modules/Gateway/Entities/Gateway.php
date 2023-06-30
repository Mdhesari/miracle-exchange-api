<?php

namespace Modules\Gateway\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'account_number', 'sheba_number',
    ];
}
