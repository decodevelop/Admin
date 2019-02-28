<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Origen_pedidos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referencia','grupo','nombre','color','transportista_principal','web','api_key'
    ];

    public function pedidos()
    {
        return $this->hasMany('App\Pedidos', 'origen_id');
    }
}
