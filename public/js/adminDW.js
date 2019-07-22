jQuery(document).ready(function($){

  $('.input-transform').click(function(){
    input_transform(this);
  });

  $('.textarea-transform').click(function(){
    textarea_transform(this);

  /*  $(this).editable({
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
