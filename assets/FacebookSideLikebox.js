/*
 Plugin Name: FacebookSideLikebox
 Author: Wojciech Krzysztofik (unholy69@gmail.com)
 Author URI: http://facbook.com/unholy69
 */
jQuery( document ).ready(function() {

    jQuery('.btn-show-likebox').click(function(e) {
        e.preventDefault();
        
        jQuery('#likebox-layer').animate({
            right: '0'
            }, 458, 'swing', function() {

            // Animation complete. CALLBACK?

        });
    });
    
    jQuery('#likebox-layer').mouseleave(function() {
        jQuery('#likebox-layer').animate({
            right: '-233'
            }, 458, 'swing', function() {

            // Animation complete. CALLBACK?

        });
    });
});