$(document).ready(function(){
    //Funcionalidad para añadir nuevos sintomas
    //$('#nuevocampo').hide();
    $( "#boton_nuevocampo" ).show();
    $( "#boton_nuevocampo" ).on("click",mostrar);
    function mostrar(){
      $('#nuevocampo').removeClass("oculto");
      $('#nuevocampo').show();
      $("main").animate({ scrollTop: $('#nuevocampo').offset().top },200);
      $( "#boton_nuevocampo" ).hide();
    }
    //Funcionalidad para la opcion de otro y otra lozalización en el tipo de sintoma
    var numSintomas = window.i;
    for (var i = 1; i <= numSintomas; i++) {
      var tipo = $('#tipo'+i).val();
      if( tipo == "Otro") {
        $('#especificar'+i).css('display', 'flex');
        $('#especificar_localizacion'+i).hide();
      }else if( tipo == "Dolor otra localización" ){
        $('#especificar_localizacion'+i).css('display', 'flex');
        $('#especificar'+i).hide();
      }else{
        $('#especificar_localizacion'+i).hide();
        $('#especificar'+i).hide();
      }
    }
    $(".tipo").change(function() {
        var id = $(this).attr("id");
        var num = id.substr(4);
        var tipo = $('#tipo'+num).val();
        if( tipo == "Otro") {
            $('#especificar'+num).css('display', 'flex')
            $('#especificar_localizacion'+num).hide();
        }else if( tipo == "Dolor otra localización" ){
            $('#especificar_localizacion'+num).css('display', 'flex');
            $('#especificar'+num).hide();
        }else{
            $('#especificar_localizacion'+num).hide();
            $('#especificar'+num).hide();
        }
    });
    //Funcionalidad para la opcion de otro y otra lozalización en los nuevos sintomas
    $('#especificar_localizacion_nueva').hide();
    $('#especificar_nueva').hide();
    $( ".tipo2" ).change(function() {
        var tipo = $('.tipo2').val();
        if( tipo == "Otro") {
            $('#especificar_nueva').css('display', 'flex')
            $('#especificar_localizacion_nueva').hide();
        }else if( tipo == "Dolor otra localización" ){
            $('#especificar_localizacion_nueva').css('display', 'flex');
            $('#especificar_nueva').hide();
        }else{
            $('#especificar_localizacion_nueva').hide();
            $('#especificar_nueva').hide();
        }
    });
});