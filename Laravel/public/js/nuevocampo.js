 $(document).ready(function(){
    //Funcionalidad para añadir nuevos sintomas
    $( "#boton_nuevocampo" ).show();
    $( "#boton_nuevocampo" ).on("click",mostrar);
    function mostrar(){
      $('#nuevocampo').removeClass("oculto");
      $('#nuevocampo').show();
      $("main").animate({ scrollTop: $('#nuevocampo').offset().top },200);
      $( "#boton_nuevocampo" ).hide();
    } 
});