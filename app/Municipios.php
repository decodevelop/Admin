<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_municipio', 'DC','nombre','provincias_id',
    ];
	
		 /**
     * Get the post that owns the comment.
     */
    public function provincias()
    {
        return $this->belongsTo('App\Comunidades');
    }
}
