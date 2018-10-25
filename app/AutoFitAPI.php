<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AutoFitAPI
 *
 * @mixin \Eloquent
 */
class AutoFitAPI extends Model
{
    protected $table = "auto_fit_apis";
    protected $guarded = ["id", "updated_at", "created_at"];
}
