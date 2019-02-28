<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materiales_productos_b extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'materiales_productos_b';
    protected $fillable = [
      'id',	'id_base','id_material'
    ];

    public function producto_base()
    {
        return $this->belongsTo('App\Productos_base','id_base');
    }

    public function material()
    {
        return $this->belongsTo('App\Materiales','id_material');
    }

}
