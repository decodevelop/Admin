<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rappels extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_proveedor', 'condiciones', 'max', 'min'
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
