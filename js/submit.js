$(document).ready(function(){
    
    $('#form').submit(function() {
          var id = window.btoa($('#tcons').val());
          var contains = 0;
          $('#sortable a').each(function(){
              if($(this).attr('href') == id){
                  $(this).parent().fadeOut('fast').fadeIn('fast');
                  contains = 1;
              }
          });
          
          $('.tablesorter').fadeOut('slow');
          $('g').each(function(){
              alert($(this).attr('id'));
              $(this).fadeOut('slow');
          });
          
          if(contains == 0){
              $.ajax({
                    url: $('#'+form.id).attr('action'),
                    type: 'POST',
                    data: $('#'+form.id).serialize(),
                    dataType: 'json',
                    success: function(json) {
                        for(var i in json){
                            $("#"+i).append(json[i]);
                            //$("#"+i).append(json[i]).fadeIn('slow');
                        }
                        //jpickeraction();
                        //$('.colorSelector', data).jPicker();
                    },
                    error: function(erro){
                        alert("Erro na comunicação com o site");
                    }
              });
          }
          
          $('#'+id).each(function(){
              $(this).fadeIn('slow');
          });
          return false;
          
    });
    
    var primeiro_layer = 0;
    $('.layer').each(function(){
        var id = $(this).children('a').attr('href');
        if(primeiro_layer == 0)
            primeiro_layer = 1;
        else
            $('#' + id).fadeOut();
    });
    
});

$('.selecionar').live('click', function(){
    //alert($(this).attr('id'));
    $(this).removeClass('selecionar');
    $(this).addClass('select');
    $(this).addClass('select');
    $(this).children(".item").removeClass('bg');
    $(this).children(".item").addClass('ativo');
    
    $('.selecionar').each(function(index) {
        $(this).removeClass("consultaativa");
    });
    $(this).addClass("consultaativa");
    
    //atualiza($(this).attr('href'));
    return false;
});

$('.select').live('click', function(){
    $(this).removeClass('select');
    $(this).addClass('selecionar');
    $(this).children(".item").removeClass('ativo');
    $(this).children(".item").addClass('bg');
    
    $('.select').each(function(index) {
        $(this).removeClass("consultaativa");
    });
    $(this).addClass("consultaativa");
    
    //atualiza($(this).attr('href'));
    return false;
});

$(function() {
    //$( "#sortable" ).sortable({beforeStop: function(event, ui) { atualiza(); }});
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
});

function atualiza(atualiza){
    var concat = "";
    if(atualiza == ''){
        $('.select').each(function(index) {
            if(concat != "") concat = concat + ";"+ $(this).attr("href");
            else concat = $(this).attr("href");
        });
    }else{concat = atualiza;}
    $.ajax({
        url: 'lib/actions/ajax.php',
        type: 'POST',
        data: {consulta: concat},
        dataType: 'json',
        success: function(json) {
            
            $("#mainlayer").html('');
            $("#tcons").html('');
            $.each(json.consulta, function(index, value) {
                
                $("#tcons").append(value+'\n');
            });
            
            $.each(json.resultado, function(index, value) {
                $("#mainlayer").append(value);
            });
           // alert(json.response);
        },
        error: function(erro){
            alert("Erro na comunicação com o site");
        }
    });

}

$('#merge').live('click', function(){
    var varr = 'merge.php?action=merge&consult=';
    $('.ativo').each(function(index) {
        varr += $(this).parent().attr('href') + ";";
    });
    $(this).attr('href', varr);
});
