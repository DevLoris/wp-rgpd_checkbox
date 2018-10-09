<?php
/*
Plugin Name: Wordpress Form Input Force
Plugin URI: http://lorispinna.com
Description: Force all forms to have a RGPD checkbox
Version: 0.1
Author: Loris
Plugin URI: http://lorispinna.com
*/

$optionsRGPD = get_option( 'rgpd_settings' );
if($optionsRGPD == false) {
    $optionsRGPD = [
        'js_selector' => "form",
        'text' => 'By checking this case...',
        'policyurl' => 'google.com',
        'link_text' => 'Lire'

    ];
}
function script_rgpdcheckbox(){
    global $optionsRGPD;
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            console.log('testttt <?php echo $optionsRGPD['js_selector']; ?>');
            var $forms = document.querySelectorAll('<?php echo $optionsRGPD['js_selector']; ?>');
            $forms.forEach(function (element) {
                console.log(element);
                var $checkbox = document.createElement('label');
                $checkbox.classList.add('rgpd');
                $checkbox.innerHTML = '<input type="checkbox" name="rgpd"> <?php echo $optionsRGPD['text']; ?> <a href="<?php echo $optionsRGPD['policyurl']; ?>" target="_blank"><?php echo $optionsRGPD['link_text']; ?></a>';

                var $last_button = element.querySelector('button');
                $last_button.insertAdjacentElement('beforebegin', $checkbox);

                element.addEventListener('submit', function() {
                    if(!$checkbox.querySelector('input').checked) {
                        event.preventDefault();
                    }
                });

                console.log($checkbox);
            });
        }, false);
    </script>
    <?php

}

add_action( 'wp_head', 'script_rgpdcheckbox' );
add_action( 'admin_menu', 'rgpd_add_admin_menu' );
add_action( 'admin_init', 'rgpd_settings_init' );


function rgpd_add_admin_menu(  ) {
    add_options_page( 'RGPD - Checkbox', 'rgpd', 'manage_options', 'rgpd', 'rgpd_options_page' );
}

function rgpd_settings_init(  ) {
    register_setting( 'pluginPage', 'rgpd_settings' );
    add_settings_section(
        'rgpd_pluginPage_section',
        __( 'Réglages', 'rgpd' ),
        'rgpd_settings_section_callback',
        'pluginPage'
    );
    add_settings_field(
        'js_selector',
        __( 'Selecteur', 'rgpd' ),
        'js_selector_render',
        'pluginPage',
        'rgpd_pluginPage_section'
    );
    add_settings_field(
        'text',
        __( 'Texte', 'rgpd' ),
        'text_render',
        'pluginPage',
        'rgpd_pluginPage_section'
    );
    add_settings_field(
        'policyurl',
        __( 'URL de la politique de confi', 'rgpd' ),
        'policyurl_render',
        'pluginPage',
        'rgpd_pluginPage_section'
    );
    add_settings_field(
        'link_text',
        __( 'Texte du lien', 'rgpd' ),
        'link_text_render',
        'pluginPage',
        'rgpd_pluginPage_section'
    );
}


function js_selector_render(  ) {
    $options = get_option( 'rgpd_settings' );
    echo ' <input type=\'text\' name=\'rgpd_settings[js_selector]\' value=\''.$options['js_selector'].'\'>';
}
function text_render(  ) {
    $options = get_option( 'rgpd_settings' );
    echo ' <textarea cols=\'40\' rows=\'5\' name=\'rgpd_settings[text]\'>'. $options['text'].'</textarea>';
}
function policyurl_render(  ) {
    $options = get_option( 'rgpd_settings' );
    echo ' <input type=\'text\' name=\'rgpd_settings[policyurl]\' value=\''.$options['policyurl'].'\'>';
}
function link_text_render(  ) {
    $options = get_option( 'rgpd_settings' );
    echo ' <input type=\'text\' name=\'rgpd_settings[link_text]\' value=\''.$options['link_text'].'\'>';
}


function rgpd_settings_section_callback(  ) {
    echo __( 'Parmet de configurer la RGPD de manière rapide sur les formulaires', 'rgpd' );
}


function rgpd_options_page(  ) {
    echo '<form action=\'options.php\' method=\'post\'>
        <h2>Options Checkbox RGPD</h2>';
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
    echo '</form>';
}

?>