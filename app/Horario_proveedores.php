<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario_proveedores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_proveedor', 'lunes', 'martes', 'miercoles', 'jueves',
        'viernes', 'sabado', 'domingo'
    ];

    public $timestamps = false;


		 /**
     * Get the post that owns the comment.
     */

     public function proveedor()
    {
      return $this->belongsTo('App\Proveedores','id_proveedor');
    }




}
