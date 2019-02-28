<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seguimiento_pedidos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origen','numero_pedido', 'id_usuario', 'id_padre' , 'mensaje'
    ];


	/**
     * Get the post that owns the comment.
     */
    public function usuario(){
        return $this->hasMany('App\User');
    }

}
