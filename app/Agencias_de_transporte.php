<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencias_de_transporte extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empresa', 'tipo',
    ];
	
		 /**
     * Get the post that owns the comment.
     */
    public function pedidos()
    {
        return $this->belongsTo('App\Pedidos');
    }
}
