 $(document).ready(function(){
    //Funcionalidad para añadir nuevos sintomas
    $( "#boton_nuevocampo" ).show();
    function mostrar(){
      $("#nuevocampo").removeClass("oculto");
      $("#nuevocampo").show();
      $("main").animate({ scrollTop: $("#nuevocampo").offset().top },200);
      $( "#boton_nuevocampo" ).hide();
    } 
    $( "#boton_nuevocampo" ).on("click",mostrar);
});