$(document).ready(function() {
    jpickeraction("colorSelector", '.');
});

function jpickeraction(id, carc){
    var cid   = carc+id;
    //var color = getColor() + '99';
    $(cid).jPicker({
        window:{
            alphaSupport: true,
            expandable: true,
            position:{x: 'screenCenter',y: 'screenCenter'}
        },
        color:{
            
            active: new $.jPicker.Color({ahex: '993300CC'})
        },
        images: {clientPath: 'plugins/jpicker/images/'}
    }, 
    
    function(color, context){
        
        var all    = color.val('all');
        var cvalue = (all && '#' + all.hex || 'none');
        var alpha  = (all && all.a + '%' || 'none');
        var id     = $(this).attr('id').toString().replace('_', '');
        var classe = '';
        
        
        if($(this).hasClass( 'bgcolor' )){
            classe = 'bgcolor';
        }else if($(this).hasClass( 'licolor' )){
            classe = 'licolor';
        }
        
        $('g').each(function(){
           if(id == $(this).attr('class')){
               if(classe == 'bgcolor'){
                    $(this).attr('stroke', cvalue);
                    $(this).attr('stroke-opacity', alpha);
               }else if(classe == 'licolor'){
                   $(this).attr('fill', cvalue);
                   $(this).attr('fill-opacity', alpha);
               }
           } 
        });
        
        
    });
}

function getColor(){
    var randomColor = Math.floor(Math.random()*16777215).toString(16);
    return randomColor;
}