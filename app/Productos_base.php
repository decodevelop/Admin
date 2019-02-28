<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos_base extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'productos_base';
    protected $fillable = [
      'id','nombre','sku_base','descripcion_general','instrucciones'
    ];
    protected $hidden = ['id_fabricante','id_proveedor'];

    public function colores()
    {
        return $this->hasMany('App\Colores_productos_b', 'id_base');
    }

    public function acabados()
    {
        return $this->hasMany('App\Acabados_productos_b', 'id_base');
    }

    public function materiales()
    {
        return $this->hasMany('App\Materiales_productos_b', 'id_base');
    }

    public function categorias()
    {
        return $this->hasMany('App\Categorias_productos_b', 'id_base');
    }

    public function imagenes()
    {
        return $this->hasMany('App\Imagenes', 'id_base');
    }

    public function proveedor()
    {
      return $this->belongsTo('App\Proveedores','id_proveedor');
    }

    public function fabricante()
    {
      return $this->belongsTo('App\Fabricantes','id_fabricante');
    }
    public function productos()
    {
        return $this->hasMany('App\Productos', 'id_base');
    }


}
