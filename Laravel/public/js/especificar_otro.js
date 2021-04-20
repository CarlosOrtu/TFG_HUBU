$(document).ready(function(){
  //Funcionalidad para la opcion de otro y otra lozalización en el tipo de sintoma
  $('.tipoDoble').each(function(){
      var tipo = $(this).val();
        if( tipo == "Otro") {
          $(this).parent().next().css('display', 'flex');
          $(this).parent().next().next().hide();
        }else if( tipo == "Dolor otra localización" ){
          $(this).parent().next().next().css('display', 'flex');
          $(this).parent().next().hide();
        }else{
          $(this).parent().next().next().hide();
          $(this).parent().next().hide();
        }
  });
  $('.tipo').each(function(){
      var tipo = $(this).val();
        if( tipo == "Otro") {
          $(this).parent().next().css('display', 'flex');
        }else{
          $(this).parent().next().hide();
        }
  });
  //Funcionalidad para la opcion de otro y otra lozalización en los nuevos sintomas
  $(".tipoDoble").change(function() {
      var tipo = $(this).val();
      if( tipo == "Otro") {
          $(this).parent().next().css('display', 'flex')
          $(this).parent().next().next().hide();
      }else if( tipo == "Dolor otra localización" ){
          $(this).parent().next().next().css('display', 'flex');
          $(this).parent().next().hide();
      }else{
          $(this).parent().next().hide();
          $(this).parent().next().next().hide();
      }
  });
  $(".tipo").change(function() {
      var tipo = $(this).val();
      if( tipo == "Otro") {
          $(this).parent().next().css('display', 'flex')
      }else{
          $(this).parent().next().hide();
      }
  });
});