<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefonos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];
	
	 /**
     * Get the comments for the blog post.
     */
    public function Clientes()
    {
        return $this->hasMany('App\Clientes');
    }
}
