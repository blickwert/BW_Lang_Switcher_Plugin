<?php /**
 * Plugin Name: Custom Polylang Language Switcher
 * Description: A custom language switcher for Polylang with shortcode support.
 * Version: 1.3.4
 * Author: Kreativbüro Blickwert
 * Text Domain: bw-lang-switcher
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Security check
}

class BW_Lang_Switcher {

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'initialize_plugin' ], 20 ); // Load after Polylang
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_shortcode( 'bw-lang-switcher', [ $this, 'render_language_switcher' ] );
        add_action( 'admin_menu', [ $this, 'add_admin_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Initializes the plugin only if Polylang is active.
     */
    public function initialize_plugin() {
        if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_the_languages' ) ) {
            add_action( 'admin_notices', [ $this, 'dependency_error_notice' ] );
            return;
        }
    }

    /**
     * Displays an error message in the admin area if Polylang is not active.
     */
    public function dependency_error_notice() {
        echo '<div class="notice notice-error"><p>' . esc_html__( 'BW Language Switcher requires the Polylang plugin. Please activate it.', 'bw-lang-switcher' ) . '</p></div>';
    }

    /**
     * Enqueues CSS and JavaScript files.
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'bw-lang-switcher', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', [], '1.0.0' );
        wp_enqueue_script( 'bw-lang-switcher', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', [ 'jquery' ], '1.0.0', true );
    }

    /**
     * Enqueues admin-specific assets.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( $hook === 'settings_page_bw-lang-switcher-settings' ) {
            wp_enqueue_media();
            wp_enqueue_script( 'bw-lang-switcher-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin-script.js', [ 'jquery' ], '1.0.0', true );
        }
    }

    /**
     * Renders the language switcher using a shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output of the language switcher.
     */
    public function render_language_switcher( $atts ) {
        $atts = shortcode_atts( [
            'available-lang' => 'true',
            'width' => '',
            'height' => '',
            'layout' => 'flag', // Default layout
        ], $atts );

        $current_lang = pll_current_language();
        $available_languages = pll_the_languages( [ 'raw' => 1 ] );
        $flags = get_option( 'bw_lang_switcher_flags', [] );

        if ( empty( $available_languages ) ) {
            return '<!-- No languages available. -->';
        }

        // Inline styles for width and height
        $style = '';
        if ( ! empty( $atts['width'] ) ) {
            $style .= 'width:' . intval( $atts['width'] ) . 'px;';
        }
        if ( ! empty( $atts['height'] ) ) {
            $style .= 'height:' . intval( $atts['height'] ) . 'px;';
        }

        ob_start();
        ?>
        <div id="bw-language-switcher">
            <!-- Current language -->
            <?php if ( isset( $available_languages[ $current_lang ] ) ): ?>
                <div class="flag-<?php echo esc_attr( $current_lang ); ?> current-language" style="<?php echo esc_attr( $style ); ?>" aria-label="<?php echo esc_attr( $available_languages[ $current_lang ]['name'] ); ?>">
                    <img src="<?php echo esc_url( $flags[$current_lang] ?? '' ); ?>" alt="<?php echo esc_attr( $available_languages[ $current_lang ]['name'] ); ?>" style="<?php echo esc_attr( $style ); ?>" />
                    <span class="screen-reader-text"><?php echo esc_html( $available_languages[ $current_lang ]['name'] ); ?></span>
                </div>
            <?php endif; ?>

            <?php if ( $atts['available-lang'] === 'true' ): ?>
                <!-- Available languages -->
                <div class="language-list hidden layout-<?php echo esc_attr( $atts['layout'] ); ?>">
                    <?php foreach ( $available_languages as $lang_code => $lang_info ): ?>
                        <?php if ( $lang_code === $current_lang ) continue; ?>
                        <a href="<?php echo esc_url( $lang_info['url'] ); ?>" 
                           class="flag-<?php echo esc_attr( $lang_code ); ?>" 
                           aria-label="<?php echo esc_attr( $lang_info['name'] ); ?>">
                            <img class="lang-img"
                            src="<?php echo esc_url( $flags[$lang_code] ?? '' ); ?>" 
                            style="<?php echo esc_attr( $style ); ?>" 
                            alt="<?php echo esc_attr( $lang_info['name'] ); ?>" 
                            /> 
                            <span class="lang-name"><?php echo esc_html( $lang_info['name'] ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Adds an admin page for setting flag URLs.
     */
    public function add_admin_settings_page() {
        add_options_page(
            'BW Language Switcher Settings',
            'Language Switcher',
            'manage_options',
            'bw-lang-switcher-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Registers the settings for flag URLs.
     */
    public function register_settings() {
        register_setting( 'bw_lang_switcher_settings', 'bw_lang_switcher_flags' );
    }

    /**
     * Renders the admin settings page with media upload functionality.
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>BW Language Switcher Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'bw_lang_switcher_settings' );
                $flags = get_option( 'bw_lang_switcher_flags', [] );
                $languages = pll_languages_list();
                foreach ( $languages as $lang_code ): ?>
                    <p>
                        <label for="flag_<?php echo esc_attr( $lang_code ); ?>">Flag for <?php echo esc_html( $lang_code ); ?>:</label>
                        <input type="hidden" name="bw_lang_switcher_flags[<?php echo esc_attr( $lang_code ); ?>]" id="flag_<?php echo esc_attr( $lang_code ); ?>" value="<?php echo esc_url( $flags[$lang_code] ?? '' ); ?>" />
                        <button type="button" class="button bw-upload-flag" data-target="#flag_<?php echo esc_attr( $lang_code ); ?>">
                            <?php esc_html_e( 'Select Flag', 'bw-lang-switcher' ); ?>
                        </button>
                        <span class="flag-preview" style="display: inline-block; margin-left: 10px;">
                            <?php if ( ! empty( $flags[$lang_code] ) ): ?>
                                <img src="<?php echo esc_url( $flags[$lang_code] ); ?>" alt="Flag" style="max-height: 30px;" />
                            <?php endif; ?>
                        </span>
                    </p>
                <?php endforeach; ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// Initialize the plugin
new BW_Lang_Switcher();
