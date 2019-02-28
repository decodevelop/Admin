<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fabricantes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

		 /**
     * Get the post that owns the comment.
     */

    public function productos_base()
    {
        return $this->hasMany('App\Productos_base', 'id_fabricante');
    }




}
