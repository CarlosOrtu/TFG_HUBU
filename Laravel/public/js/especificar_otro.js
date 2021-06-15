$(document).ready(function(){
  function cambiarTipoDoble() {
    var tipo = $(this).val();
    if( tipo ==== "Otro") {
      $(this).parent().next().css("display", "flex");
      $(this).parent().next().next().hide();
    }else if( tipo === "Dolor otra localización" || tipo === "Farmaco en ensayo clínico" ){
      $(this).parent().next().next().css("display", "flex");
      $(this).parent().next().hide();
    }else{
      $(this).parent().next().next().hide();
      $(this).parent().next().hide();
    }
  }

  function cambiarTipo() {
    var tipo = $(this).val();
    if( tipo === "Otro" || tipo === "Fumador" || tipo === "Exfumador" || tipo === "nacimiento") {
      $(this).parent().next().css("display", "flex");
    }else{
      $(this).parent().next().hide();
    }
  }

  function cambiarTipoGrafica() {
    var tipo = $(this).val();
    if(tipo === "nacimiento") {
        $(this).parent().parent().next().css("display", "flex");
    }else{
        $(this).parent().parent().next().hide();
    }
  }

  function cambiarTipoNuevo() {
    var tipo = $(this).val();
    if( tipo === "Progresión") {
        $(this).parent().next().css("display", "flex");
        $(this).parent().next().next().next().css("display", "flex");
        if($(this).parent().next().find("select").val() === "Otro")
          $(this).parent().next().next().css("display", "flex");
    }else{
        $(this).parent().next().hide();
        $(this).parent().next().next().hide();
        $(this).parent().next().next().next().hide();
    }
  }

  function cambiarTipoTres() {
    var tipo = $(this).val();
    if( tipo === "Si" || tipo === "Fallecido") {
      $(this).parent().next().css("display", "flex");
      $(this).parent().next().next().css("display", "flex");
    }else{
      $(this).parent().next().next().hide();
      $(this).parent().next().hide();
    }
  }

  $(".tipoDoble").each(cambiarTipoDoble());

  $(".tipoDoble").change(cambiarTipoDoble());

  $(".tipo").each(cambiarTipo());

  $(".tipo").change(cambiarTipo());

  $(".tipoGrafica").change(cambiarTipoGrafica());

  $(".tipoGrafica").each(cambiarTipoGrafica());

  $(".tipoNuevo").each(cambiarTipoNuevo());

  $(".tipoNuevo").change(cambiarTipoNuevo());

  $(".tipoTres").each(cambiarTipoTres());
  
  $(".tipoTres").change(cambiarTipoTres());
});