<?php

namespace Nikitakiselev\DemoCart\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'data'];
}
