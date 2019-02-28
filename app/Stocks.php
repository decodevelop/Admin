<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'stock', 'stock_control', 'stock_virtual'
    ];

    public function producto()
    {
      return $this->belongsTo('App\Productos','id_producto');
    }



}
