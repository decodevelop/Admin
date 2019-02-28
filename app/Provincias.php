<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincias extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provincia', 'comunidades_id',
    ];
	
		 /**
     * Get the post that owns the comment.
     */
    public function comunidades()
    {
        return $this->belongsTo('App\Comunidades');
    }
}
