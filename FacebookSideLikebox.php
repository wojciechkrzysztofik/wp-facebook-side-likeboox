<?php
/*
Plugin Name: Facebook Side Likebox
Plugin URI: http://www.facebook.com/unholy69
Description: Widget displays facebook likebox on the right side of the page.
Author: Wojciech Krzysztofik (unholy69@gmail.com)
Version: 1.0
*/
add_action( 'init', function(){
    new FacebookSideLikebox();
} );

/**
 * Class FacebookSideLikebox
 */
class FacebookSideLikebox
{
    /**
     * @var string
     */
    const MENU_SLUG = 'facebook-side-likebox';
    /**
     * @var string
     */
    const PLUGIN_NAME = 'Facebook Side Likebox';
    /**
     * @var string
     */
    const OPTION_GROUP = 'facebook-side-likebox-options';
    /**
     * @var string
     */
    const SECTION_ID = 'facebook-side-likebox-main-section';
    const FORM_URL_ID = 'url';
    /**
     * Constructor that sets up the hooks
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'adminMenuItem') );
        add_action( 'admin_init', array( $this, 'registerPluginOptions') );
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
        add_action( 'wp_footer', array( $this, 'injectLikebox'), 100 );
    }
    /**
     * Register plugin stylesheet
     */
    public function register_plugin_styles()
    {
        wp_register_style('FacebookSideLikebox', plugins_url('wp-facebook-side-likebox/assets/FacebookSideLikebox.css'));
        wp_enqueue_style('FacebookSideLikebox');
    }

    public function register_plugin_scripts()
    {
        wp_register_script('FacebookSideLikebox', plugins_url('wp-facebook-side-likebox/assets/FacebookSideLikebox.js'));
        wp_enqueue_script('FacebookSideLikebox');
    }

    /**
     * Add custom sub-menu into general options menu
     * Needs to be public, because it implements the hook.
     */
    public function adminMenuItem()
    {
        add_submenu_page(
            'options-general.php',
            self::PLUGIN_NAME,
            self::PLUGIN_NAME,
            'manage_options',
            self::MENU_SLUG,
            array( $this, 'displayAdminSettings')
        );
    }

    /**
     *  Render the form with settings
     */
    public function displayAdminSettings()
    {
        ?>
        <div class="wrap">
            <h2><?php print self::PLUGIN_NAME; ?></h2>
            <?php settings_errors(); ?>
            <form action="options.php" method="post">
                <?php settings_fields( self::OPTION_GROUP ); ?>
                <?php do_settings_sections( self::MENU_SLUG ); ?>
                <?php submit_button( __( 'Save/Update', MENU_SLUG ) ) ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register options on the page
     */
    public function registerPluginOptions()
    {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_GROUP,
            array( $this, 'validate_settings' )
        );
        add_settings_section(
            self::SECTION_ID,
            __( 'Main settings', MENU_SLUG ),
            function() {},
            self::MENU_SLUG
        );
        $options = get_option( self::OPTION_GROUP );
        add_settings_field(
            self::FORM_URL_ID,
            __( 'Fanpage name', MENU_SLUG ),
            array( $this, 'renderInputField' ),
            self::MENU_SLUG,
            self::SECTION_ID,
            array(
                'name' => self::FORM_URL_ID,
                'value' => isset( $options[self::FORM_URL_ID] ) ? $options[self::FORM_URL_ID] : ''
            )
        );
    }
    /**
     * Renders a text input field
     * @param array $args
     */
    public function renderInputField( array $args )
    {
        ?>
        <input type="text" name="<?php print self::OPTION_GROUP; ?>[<?php print $args['name']; ?>]"
            value="<?php print $args['value'] ?>"/>
        <?php
    }
    /**
     * Validate settings and store it in database
     */
    public function validate_settings( $data )
    {
        
        return $data;
    }
    
    public function injectLikebox()
    {
        $options = get_option(self::OPTION_GROUP);
        ?>
        <aside id="likebox-layer">
            <a href="" class="btn-show-likebox">
                <img src="<?php echo plugins_url('wp-facebook-side-likebox/assets/facebook-logo.png'); ?>" alt="Facebook">
            </a>
            <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $options['url'] ?>&amp;width=232&amp;height=300&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=1517835671773096" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:232px; height:300px;" allowTransparency="true"></iframe>
        </aside>
        <?php
    }
}