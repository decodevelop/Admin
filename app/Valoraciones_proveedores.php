<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valoraciones_proveedores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_proveedor', 'id_usuario', 'puntuacion', 'comentario'
    ];


	/**
     * Get the post that owns the comment.
     */
    public function usuario(){
        return $this->hasMany('App\User');
    }

}
