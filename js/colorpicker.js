$(document).ready(function() {
    var color_picker_id_selected = '';
    $('.colorSelector').ColorPicker({
        color: '#0000ff',
        onShow: function (colpkr) {
                color_picker_id_selected = $(this).attr('id');
                $(colpkr).fadeIn(500);
                return false;
        },
        onHide: function (colpkr) {
                color_picker_id_selected = "";
                $(colpkr).fadeOut(500);
                return false;
        },
        onChange: function (hsb, hex, rgb) {
                var temp = '#'+color_picker_id_selected+' div';
                $(temp).css('backgroundColor', '#' + hex);
        }
    });
    
});