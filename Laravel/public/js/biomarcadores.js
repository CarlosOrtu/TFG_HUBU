  $(document).ready(function(){
    //Función que devuelve los inputs en cuanto se da click a un checkbox
    $('.casilla').change(function() {
      let id = "#" + this.id;
      let inputs = $(id).attr('num_input');
      if($(id+'_tipo_input').val() == "Otra"){
        $(id+'_tipo_input_especificar').css('display','flex');
      }else if($(id+'_subtipo_input').val() == "Otra"){
        $(id+'_subtipo_input_especificar').css('display','flex');
      }
      if(inputs == 2){
        if($(id).prop('checked')){
           $(id+'_subtipo').css('display','flex');
           $(id+'_tipo').css('display','flex');
        }else{
           $(id+'_subtipo').css('display','none');
           $(id+'_tipo').css('display','none');
           $(id+'_tipo_input_especificar').css('display','none');
           $(id+'_subtipo_input_especificar').css('display','none');
        }
      }else{
        if($(id).prop('checked')){
           $(id+'_tipo').css('display','flex');
           if($(id+'_tipo_input').attr('otro_imput') == $(id+'_tipo_input').val()){
            $(id+'_subtipo').css('display','flex');
           }
        }else{
           $(id+'_tipo').css('display','none');
           $(id+'_tipo_input_especificar').css('display','none');
           $(id+'_subtipo_input_especificar').css('display','none');
           if($(id+'_tipo_input').attr('subtipo') == this.id+"_subtipo"){
            $(id+'_subtipo').css('display','none');
           }
        }
      }
    });
    //Función que comprueba si un input es de un tipo especifico para devolver el subtipo
    $('.input_cambio').change(function() {
      let id = "#" + this.id;
      let valorActual = $(id).val();
      let valorActivar = $(id).attr('otro_imput');
      let inputActivar = $(id).attr('subtipo');
      if(valorActual == "Otra"){
        $(id+'_especificar').css('display','flex');
      }else{
        $(id+'_especificar').css('display','none');
      }
      if(valorActual == valorActivar){
        $('#'+inputActivar).css('display','flex');
        if($('#'+inputActivar+'_input').val() == "Otra"){
          $('#'+inputActivar+'_input_especificar').css('display','flex');
        }
      }else{
        $('#'+inputActivar).css('display','none');
        $('#'+inputActivar+'_input_especificar').css('display','none');
      }
    });
  });