$(document).ready(function() {
    
    jpickeraction("colorSelector", '.');
    /*$('.colorSelector').each(function(){
        var id = $(this).attr('id');
        jpickeraction(id, '#');
    });*/
    
});

function jpickeraction(id, carc){
    var cid = carc+id;
    $(cid).jPicker({
        window:{
            alphaSupport: true,
            active: new $.jPicker.Color({ ahex: '993300FF' }),
            expandable: true,
            position:{x: 'screenCenter',y: 'screenCenter'}
        },
        images: {clientPath: 'plugins/jpicker/images/'}
    });
}