<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidencias_usuarios extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_usuario_incidencia', 'id_usuario_asignado_incidencia', 'mensaje', 'prioridad', 'estado', 'adjuntos'
    ];
	
	
	/**
     * Get the post that owns the comment.
     */
    public function usuario(){
        return $this->hasMany('App\User');
    }
	
}
