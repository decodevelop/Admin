<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','nombre','email','telefono', 'plazo_entrega', 'plazo_entrega_web',
        'envio', 'metodo_pago', 'precio_esp_campana', 'logistica',
        'contrato', 'ultima_visita', 'observaciones', 'valoracion_media',
        'listo_para_vender', 'contrato_pdf', 'vacaciones_inicio', 'vacaciones_fin'
    ];

		 /**
     * Get the post that owns the comment.
     */

    public function productos()
    {
        return $this->hasMany('App\Productos_pedidos', 'id_proveedor');
    }

    public function productos_base()
    {
        return $this->hasMany('App\Productos_base', 'id_proveedor');
    }

    public function get_url_contrato()
   {
     dd(\Storage::disk('pdfs')->getDriver()->getAdapter()->applyPathPrefix($this->id.'_contrato.pdf'));
     $url = explode('public\\',\Storage::disk('pdfs')->getDriver()->getAdapter()->applyPathPrefix($this->id.'_contrato.pdf'));
     //dd($url);
       return $url[1];
   }


}
