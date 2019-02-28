<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pedidos_id', 'SKU', 'nombre', 'variante', 'cantidad', 'peso', 'precio',
    ];
	
		
	 /**
     * Get the post that owns the comment.
     */
    public function pedidos()
    {
        return $this->belongsTo('App\Pedidos');
    }
}
