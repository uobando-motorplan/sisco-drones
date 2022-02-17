<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    const IVA = 8;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'value',
    ];
}
