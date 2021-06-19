$(document).ready(function(){
  function cambiarTipoDoble(element) {
    var tipo = $(element).val();
    if( tipo === "Otro") {
      $(element).parent().next().css("display", "flex");
      $(element).parent().next().next().hide();
    }else if( tipo === "Dolor otra localización" || tipo === "Farmaco en ensayo clínico" ){
      $(element).parent().next().next().css("display", "flex");
      $(element).parent().next().hide();
    }else{
      $(element).parent().next().next().hide();
      $(element).parent().next().hide();
    }
  }

  function cambiarTipo(element) {
    var tipo = $(element).val();
    if( tipo === "Otro" || tipo === "Fumador" || tipo === "Exfumador" || tipo === "nacimiento") {
      $(element).parent().next().css("display", "flex");
    }else{
      $(element).parent().next().hide();
    }
  }

  function cambiarTipoGrafica(element) {
    var tipo = $(element).val();
    if(tipo === "nacimiento") {
        $(element).parent().parent().next().css("display", "flex");
    }else{
        $(element).parent().parent().next().hide();
    }
  }

  function cambiarTipoNuevo(element) {
    var tipo = $(element).val();
    if( tipo === "Progresión") {
        $(element).parent().next().css("display", "flex");
        $(element).parent().next().next().next().css("display", "flex");
        if($(element).parent().next().find("select").val() === "Otro") {
          $(element).parent().next().next().css("display", "flex");
        }
    }else{
        $(element).parent().next().hide();
        $(element).parent().next().next().hide();
        $(element).parent().next().next().next().hide();
    }
  }

  function cambiarTipoTres(element) {
    var tipo = $(element).val();
    if( tipo === "Si" || tipo === "Fallecido") {
      $(element).parent().next().css("display", "flex");
      $(element).parent().next().next().css("display", "flex");
    }else{
      $(element).parent().next().next().hide();
      $(element).parent().next().hide();
    }
  }

  $(".tipoDoble").each(function() {
    cambiarTipoDoble(this);
  });

  $(".tipoDoble").change(function() {
    cambiarTipoDoble(this);
  });

  $(".tipo").each(function() {
    cambiarTipo(this);
  });

  $(".tipo").change(function() {
    cambiarTipo(this);
  });

  $(".tipoGrafica").change(function() {
    cambiarTipoGrafica(this);
  });

  $(".tipoGrafica").each(function() {
    cambiarTipoGrafica(this);
  });

  $(".tipoNuevo").each(function() {
    cambiarTipoNuevo(this);
  });

  $(".tipoNuevo").change(function() {
    cambiarTipoNuevo(this);
  });

  $(".tipoTres").each(function() {
    cambiarTipoTres(this);
  });
  
  $(".tipoTres").change(function() {
    cambiarTipoTres(this);
  });
});