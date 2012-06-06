$(document).ready(function(){
    $('.colorSelector').jPicker({
        window:{
            alphaSupport: true,
            active: new $.jPicker.Color({ ahex: '993300FF' }),
            expandable: true,
            position:{x: 'screenCenter',y: 'screenCenter'}
        },
        images: {clientPath: 'plugins/jpicker/images/'}
    });
});


