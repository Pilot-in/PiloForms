<?php
/**
 * Plugin Name: Pilot'in - Espace client
 * Description: Permet d'ajouter un espace protégé, accessible en étant connecté.
 * Version: 1.0
 * Author: Pilot'in
 * Author URI: https://www.pilot-in.com
 * License: GPL2
 */

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Pilotin_My_Account' ) ) {

    /**
     * Class Pilotin_My_Account
     *
     * @package Pilotin_My_Account
     */
    class Pilotin_My_Account {

        // Plugin version
        public $version = '1.0';

        // Pilo'Press
        public $pip = false;

        /**
         * Pilot'in - My Account constructor.
         */
        public function __construct() {
            // Do nothing.
        }

        /**
         * Initialize plugin
         */
        public function initialize() {

            // Constants
            define( 'PMA_FILE', __FILE__ );
            define( 'PMA_PATH', plugin_dir_path( __FILE__ ) );
            define( 'PMA_URL', plugin_dir_url( __FILE__ ) );
            define( 'PMA_BASENAME', plugin_basename( __FILE__ ) );

            // Init
            include_once PMA_PATH . 'init.php';

            // Load
            add_action( 'plugins_loaded', array( $this, 'load' ) );
        }

        /**
         * Load classes
         */
        public function load() {

            // Check if Pilo'Press is activated
            if ( !$this->has_pip() ) {
                return;
            }

            // Includes
            add_action( 'acf/init', array( $this, 'includes' ) );

        }

        /**
         * Include files
         */
        public function includes() {

            // Field groups
            $options_path    = apply_filters( 'pma/paths/options', PMA_PATH . '/field-groups/pip-my-account-options.php' );
            $change_password = apply_filters( 'pma/paths/change_password', PMA_PATH . '/field-groups/change-password.php' );
            $lost_password_1 = apply_filters( 'pma/paths/lost_password_1', PMA_PATH . '/field-groups/lost-password-1.php' );
            $lost_password_2 = apply_filters( 'pma/paths/lost_password_2', PMA_PATH . '/field-groups/lost-password-2.php' );
            include( $options_path );
            include( $change_password );
            include( $lost_password_1 );
            include( $lost_password_2 );

            // Helpers
            pma_include( 'includes/helpers.php' );

            // Classes
            pma_include( 'includes/class-main.php' );

        }

        /**
         * Check if Pilo'Press is activated
         *
         * @return bool
         */
        public function has_pip() {

            // If Pilo'Press already available, return
            if ( $this->pip ) {
                return true;
            }

            // Check Pilo'Press
            $pip_exists = class_exists( 'PiloPress' );
            if ( !$pip_exists ) {
                return false;
            }

            // Get Pilo'Press instance
            $pip_instance = new PiloPress();
            if ( !$pip_instance ) {
                return false;
            }

            // Check ACF
            $acf = $pip_instance->has_acf();
            if ( !$acf ) {
                return false;
            }

            // Pilo'Press is activated
            $this->pip = true;

            return $this->pip;
        }

    }
}

/**
 * Instantiate Pilot'in My Account
 *
 * @return Pilotin_My_Account
 */
function pma_init() {
    global $pma;

    if ( !isset( $pma ) ) {
        $pma = new Pilotin_My_Account();
        $pma->initialize();
    }

    return $pma;
}

// Instantiate
pma_init();

register_activation_hook( __FILE__, 'pma_activation' );

/**
 * Activation hook
 *
 * @param $network_wide
 */
function pma_activation( $network_wide ) {

    // Import ACFE Forms
    pma_import_acfe_forms();
}

/**
 * Import forms
 */
function pma_import_acfe_forms() {

    // Do this only if PiloPress addon & ACF are available
    if ( !defined( 'PMA_PATH' ) || !function_exists( 'acf' ) ) {
        return;
    }

    // Forms to import
    $forms = array(
        'password-form',
        'lost-password-1',
        'lost-password-2',
    );

    // Browse forms
    foreach ( $forms as $form_name ) {

        // Get exported form
        $form_json = file_get_contents( PMA_PATH . 'forms/' . $form_name . '.json' ); // phpcs:ignore
        if ( !$form_json ) {
            return;
        }

        // Decode json
        $default_form_data = json_decode( $form_json, true );

        // Force initialize of ACF tools (only loaded on Tools page by default)
        acf()->admin_tools = new acf_admin_tools();
        acf()->admin_tools->load();

        // Initialise ACFE Tool Import Form & import form
        $acfe_tool_import_form = new ACFE_Admin_Tool_Import_Form();
        $acfe_tool_import_form->import_external( $default_form_data );
    }
}
