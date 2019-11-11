<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Festivos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'festivos';
    protected $fillable = [
      'id','fecha'
    ];

    public function todos(){

      return Festivos::get();
    }

}
