<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transportistas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

		 /**
     * Get the post that owns the comment.
     */

    public function productos()
    {
        return $this->hasMany('App\Productos_pedidos', 'id_transportista');
    }



}
