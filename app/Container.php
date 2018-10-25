<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Container
 *
 * @mixin \Eloquent
 */
class Container extends Model
{
    protected $table = "container";

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
