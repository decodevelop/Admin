<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_amazon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','nombre','referencia','codigo_ean','asin'
    ];
}
