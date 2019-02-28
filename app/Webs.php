<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webs extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id','nombre','url'
    ];

    public function productos()
    {
        return $this->hasMany('App\Webs_productos', 'id_web');
    }

}
