<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seguimiento_proveedores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_proveedor', 'id_usuario', 'mensaje'
    ];


	/**
     * Get the post that owns the comment.
     */
    public function usuario(){
        return $this->hasMany('App\User');
    }

}
