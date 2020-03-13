<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'apodo', 'permisos', 'puesto_trabajo', 'email', 'clave', 'imagen_perfil','nombre','primer_apellido','segundo_apellido','fecha_nacimiento','rol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'clave', 'remember_token',
    ];

	/**
     * Override getAuthPassword to override modified passwname.
     *
     * @var array
     */
	public function getAuthPassword(){
		return $this->clave;
	}
  public function hasRole($role)
{
  if ($this->rol == $role) {
      return true;
  }
  return false;
}

}
