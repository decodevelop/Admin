jQuery(document).ready(function($){
  $('#añadirPersonalButton').click(function(){
    var personal_code = "<divclass=\"col-xs-12 product-combi\"><div class=\"col-sm-6 mt-5\"><table class=\"table\"><tbody><tr><td>Cargo:<\/td><td><input name=\"pers_cargo[]\" type=\"text\" class=\"form-control\"><\/td><\/tr><tr><td>Nombre:<\/td><td><input name=\"pers_nombre[]\" type=\"text\" class=\"form-control\"><\/td><\/tr><tr><td>Correo:<\/td><td><input name=\"pers_correo[]\" data-type=\"text\" class=\"form-control\"><\/td><\/tr><tr><td>Teléfono:<\/td><td><input name=\"pers_telefono[]\" data-type=\"number\" class=\"form-control\"><\/td><\/tr><\/tbody><\/table><\/div><\/div>";

    $("#personal").append(personal_code);
  });

  $('#añadirDireccionButton').click(function(){
    var direccion_code = "<div class=\"product-combi col-xs-12\"><div class=\"col-lg-6 col-xs-12\"><table class=\"table\"><tbody><tr><td style=\"border-top: 0px; width: 20%;\"></td><td style=\"border-top: 0px;\"><strong>Facturación</strong></td></tr><tr><td>Dirección:</td><td><input name=\"direccion_facturacion[]\" data-type=\"text\" class=\"form-control\" value=\"\"></td></tr><tr><td>Código Postal:</td><td><input name=\"cp_facturacion[]\" type=\"number\" class=\"form-control\"value=\"\"></td></tr><tr><td>Ciudad:</td><td><input name=\"ciudad_facturacion[]\" data-type=\"text\" class=\"form-control\" value=\"\"></td></tr><tr><td>Estado:</td><td><input name=\"estado_facturacion[]\" data-type=\"text\" class=\"form-control\" value=\"\"></td></tr><tr><td>País:</td><td><input name=\"pais_facturacion[]\" data-type=\"text\" class=\"form-control\"value=\"\"></td></tr></tbody></table></div><div class=\"col-lg-6 col-xs-12\"><table class=\"table\"><tbody><tr><td style=\"border-top: 0px; width: 20%;\"></td><td style=\"border-top: 0px;\"><strong>Envío</strong></td></tr><tr><td>Dirección:</td><td><input name=\"direccion_envio[]\" data-type=\"text\" class=\"form-control\"value=\"\"></td></tr><tr><td>Código Postal:</td><td><input name=\"cp_envio[]\" type=\"number\" class=\"form-control\"value=\"\"></td></tr><tr><td>Ciudad:</td><td><input name=\"ciudad_envio[]\" data-type=\"text\" class=\"form-control\"value=\"\"></td></tr><tr><td>Estado:</td><td><input name=\"estado_envio[]\" data-type=\"text\" class=\"form-control\" value=\"\"></td></tr><tr><td>País:</td><td><input name=\"pais_envio[]\"data-type=\"text\" class=\"form-control\" value=\"\"></td></tr></tbody></table></div></div>";

    $("#direcciones").append(direccion_code);

    var cont_direcciones = $("#cont_direcciones").val();
    cont_direcciones = parseInt(cont_direcciones) + 1;
    $("#cont_direcciones").val(cont_direcciones);
  });

  $('.input-transform').click(function(){
    input_transform(this);
  });

  $('.textarea-transform').click(function(){
    textarea_transform(this);

    /*$(this).editable({
    inlineMode:false
  })*/
});

$('.textarea-transform-init').each(function(){
  textarea_transform(this);
});

$(".addCombi").click(function(){
  var clone = $(".product-combi").first().clone();

  clone.appendTo("#product-combis");
});

$(".addImageSelector").click(function(){
  var clone = $(".selector-imagen").first().clone();

  clone.appendTo("#selector_imagen");
});



$('[name^="selectpickmult_"]').change(function(){
  var inputName = $( this ).attr("name").split("_")[1];
  $('[name="'+inputName+'"]').val($(this).val());
});


$(".rating-stars").ratingStars();


});


function input_transform(thisInput){

  $(thisInput).fadeOut('fast');

  $("<input>" ,{
    'class': 'form-control',
    'style': 'display:none',
    'value': $(thisInput).html(),
    'name': $(thisInput).data('inputname')

  }).appendTo($(thisInput).parent()).delay(200).fadeIn('fast');

}

function textarea_transform(thisInput){
  $(thisInput).hide('fast');

  $("<textarea>" ,{
    'class': 'form-control textarea-'+$(thisInput).data('inputname'),
    'style': 'display:none',
    'text': $.trim($(thisInput).html()),
    'name': $(thisInput).data('inputname'),
    'rows': $(thisInput).data('rows'),
    'cols': $(thisInput).data('cols'),

  }).appendTo($(thisInput).parent()).delay(400).show('slow').richText();



}
