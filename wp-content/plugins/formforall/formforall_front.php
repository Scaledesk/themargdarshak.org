<?php
/*
FormForAll :: Font-office features
*/
add_shortcode('formforall', 'formforall_display_shortcode');

function formforall_display_shortcode($atts, $content=null) {
    return formforall_front_display($atts['formid'], $atts['ts']);
}
?>
