<?php

namespace Modules\Bank\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'bIdentif_code',
		'name',
		'location',
		'established_date',
		'website',
    ];
}
