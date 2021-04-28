<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'regcode',
        'sepa',
        'name',
        'name_before_quotes',
        'name_in_quotes',
        'name_after_quotes',
        'without_quotes',
        'regtype',
        'regtype_text',
        'type',
        'type_text',
        'registered',
        'terminated',
        'closed',
        'address',
        'index',
        'addressid',
        'region',
        'city',
        'atvk',
        'reregistration_term',
    ];

    protected $casts = [
        'registered' => 'datetime',
        'terminated' => 'datetime',
    ];
}
