<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webs_productos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',	'id_producto','id_web'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Productos','id_producto');
    }

    public function web()
    {
        return $this->belongsTo('App\Webs','id_web');
    }

}
