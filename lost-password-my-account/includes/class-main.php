<?php

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Pilotin_My_Account_Main' ) ) {

    /**
     * Class Pilotin_My_Account_Main
     */
    class Pilotin_My_Account_Main {

        /**
         * Option page ID
         *
         * @var string
         */
        public static $options_id;

        /**
         * Templates
         *
         * @var mixed|void
         */
        protected $templates;

        /**
         * Templates directory
         *
         * @var mixed|void
         */
        protected $template_dir;

        /**
         * Pilotin_My_Account_Main constructor.
         */
        public function __construct() {
            // Initiate variables
            self::$options_id   = apply_filters( 'pma/options_id', 'pip-my-account-options' );
            $this->template_dir = apply_filters( 'pma/template_dir', PMA_PATH . 'templates/' );
            $this->templates    = apply_filters(
                'pma/templates',
                array(
                    'template-dashboard.php' => __( 'Espace client', 'pilot-in' ),
                )
            );

            // WP hooks
            add_filter( 'theme_page_templates', array( $this, 'add_templates' ) );
            add_filter( 'wp_insert_post_data', array( $this, 'register_templates' ) );
            add_filter( 'template_include', array( $this, 'view_template' ) );
            add_action( 'init', array( $this, 'init_hook' ) );
            add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_menu_item' ) );

            // ACFE hooks
            add_action( 'acfe/form/validation/change_password', array( $this, 'validate_change_password' ), 10, 3 );
            add_action( 'acfe/form/submit/change_password', array( $this, 'modify_password' ), 10, 2 );
            add_action( 'acfe/form/validation/lost_password_step_1', array( $this, 'validate_lost_password_step_1' ), 10, 3 );
            add_action( 'acfe/form/validation/lost_password_step_2', array( $this, 'validate_lost_password_step_2' ), 10, 3 );
            add_action( 'acfe/form/submit/lost_password_step_1', array( $this, 'lost_password_step_1' ), 10, 3 );
            add_action( 'acfe/form/submit/lost_password_step_2', array( $this, 'lost_password_step_2' ), 10, 3 );
            add_filter( 'acf/prepare_field/name=lost_pwd_user_id', array( $this, 'fill_user_id' ) );

        }

        /**
         * Shortcode for logout URL
         *
         * @param $item
         *
         * @return mixed
         */
        public function setup_nav_menu_item( $item ) {
            global $pagenow;

            if ( $pagenow != 'nav-menus.php'
                 && !defined( 'DOING_AJAX' )
                 && isset( $item->url ) ) {

                if ( $item->url === '#logout_url#' ) {
                    if ( !is_user_logged_in() ) {
                        return $item;
                    }

                    $item->url = wp_logout_url( home_url() );
                }

                $item->url = esc_url( $item->url );
            }

            return $item;
        }

        /**
         * Redirect user if needed
         */
        public static function maybe_redirect_user() {
            do_action( 'pma/before_maybe_redirect_user' );

            // User logged in
            if ( is_user_logged_in() ) {

                // Get dashboard URLs from menu
                $dashboard_urls = array();
                $dashboard_menu = pma_get_dashboard_menu();
                if ( $dashboard_menu ) {
                    $menu_items = wp_get_nav_menu_items( pma_get_dashboard_menu() );
                    if ( $menu_items ) {
                        foreach ( $menu_items as $menu_item ) {
                            $dashboard_urls[] = pip_maybe_get( $menu_item, 'url' );
                        }
                    }
                }

                // Get dashboard page
                $dashboard_page = get_field( 'dashboard_page', self::$options_id );

                // Get URL without params
                $cur_url = untrailingslashit( home_url() ) . parse_url( acf_get_current_url(), PHP_URL_PATH );

                // If paged param is set, remove it from URL
                $current_page = get_query_var( 'paged' );
                if ( $current_page ) {
                    $parsed_url = parse_url( $cur_url );
                    $path       = acf_maybe_get( $parsed_url, 'path' );
                    $path_parts = explode( '/', $path );
                    $page_key   = array_search( 'page', $path_parts );
                    $new_path   = array_slice( $path_parts, 0, $page_key, true );
                    $cur_url    = home_url() . implode( '/', $new_path );
                }

                // Allow URLs
                $allowed_urls = array();

                // Allow checkout page
                $checkout_page = pma_get_checkout_page();
                if ( $checkout_page ) {
                    $allowed_urls[] = get_permalink( $checkout_page );
                }

                // Allow thank you page
                $thank_you_page = pma_get_thank_you_page();
                if ( $thank_you_page ) {
                    $allowed_urls[] = get_permalink( $thank_you_page );
                }

                // Allow custom URLs
                $allowed_urls = apply_filters( 'pma/allowed_urls', $allowed_urls );

                // Allow by-pass
                $dashboard_bypass = apply_filters( 'pma/dashboard_bypass_redirect', false );

                // If current URL is in dashboard menu or is dashboard menu, do not redirect
                if ( !$dashboard_page
                     || $cur_url === get_permalink( $dashboard_page )
                     || in_array( $cur_url, $dashboard_urls )
                     || in_array( $cur_url, $allowed_urls )
                     || $dashboard_bypass ) {
                    return;
                }

                // Redirect user to dashboard
                wp_safe_redirect( get_permalink( $dashboard_page ) );
                exit();

            } else {
                // User not logged in

                // Get pages
                $login_page    = pma_get_login_page();
                $register_page = pma_get_register_page();

                // Register or login page
                if ( ( $login_page && acf_get_current_url() === get_permalink( $login_page ) )
                     || ( $register_page && acf_get_current_url() === get_permalink( $register_page ) ) ) {
                    return;
                }

                // Get redirect page
                $redirect_page = pma_get_logged_out_redirect_page();
                if ( !$redirect_page ) {
                    return;
                }

                // Redirect user
                wp_safe_redirect( get_permalink( $redirect_page ) );
                exit();
            }
        }

        /**
         * Register main options page
         */
        public function init_hook() {
            if ( function_exists( 'acf_add_options_page' ) ) {

                // Register site options page
                acf_add_options_page(
                    array(
                        'page_title' => __( 'Espace client', 'pilot-in' ),
                        'menu_title' => __( 'Espace client', 'pilot-in' ),
                        'menu_slug'  => self::$options_id,
                        'post_id'    => self::$options_id,
                        'icon_url'   => 'dashicons-lock',
                        'position'   => 81,
                        'autoload'   => true,
                    )
                );
            }
        }

        /**
         * Add custom templates
         *
         * @param $posts_templates
         *
         * @return array
         */
        public function add_templates( $posts_templates ) {
            $posts_templates = array_merge( $posts_templates, $this->templates );

            return $posts_templates;
        }

        /**
         * Register templates
         *
         * @param $attrs
         *
         * @return mixed
         */
        public function register_templates( $attrs ) {

            $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

            $templates = wp_get_theme()->get_page_templates();
            if ( empty( $templates ) ) {
                $templates = array();
            }

            wp_cache_delete( $cache_key, 'themes' );
            $templates = array_merge( $templates, $this->templates );
            wp_cache_add( $cache_key, $templates, 'themes', 1800 );

            return $attrs;
        }

        /**
         * Display templates
         *
         * @param $template
         *
         * @return string
         */
        public function view_template( $template ) {
            // Get global post
            global $post;

            // Return template if post is empty
            if ( !$post ) {
                return $template;
            }

            // Get template
            $selected_template = get_post_meta( $post->ID, '_wp_page_template', true );

            // Return default template if we don't have a custom one defined
            if ( !isset( $this->templates[ $selected_template ] ) ) {
                return $template;
            }

            // Get file URL
            $file = $this->template_dir . $selected_template;

            // Just to be safe, we check if the file exist first
            if ( file_exists( $file ) ) {
                return $file;
            } else {
                echo $file;
            }

            // Return template
            return $template;
        }

        /**
         * Validate change password form's fields
         *
         * @param $form
         * @param $post_id
         * @param $alias
         */
        public function validate_change_password( $form, $post_id, $alias ) {
            // Get user
            $current_user = get_current_user_id();
            $current_user = get_user_by( 'ID', $current_user );

            // Can't get user
            if ( !is_a( $current_user, 'WP_User' ) ) {
                acfe_add_validation_error( '', __( "L'utilisateur n'a pas été trouvé.", 'pilot-in' ) );
            }

            // Get fields
            $current_password = get_field( 'current_password' );
            $new_password     = get_field( 'new_password' );
            $confirm_password = get_field( 'new_password_confirmation' );

            // Wrong password
            if ( !wp_check_password( $current_password, $current_user->user_pass ) ) {
                acfe_add_validation_error( 'current_password', __( 'Mot de passe incorrect.', 'pilot-in' ) );
            }

            // Different passwords
            if ( $new_password !== $confirm_password ) {
                acfe_add_validation_error( 'new_password_confirmation', __( 'Les mots de passe sont différents', 'pilot-in' ) );
            }
        }

        /**
         * Modify password
         *
         * @param $form
         * @param $post_id
         */
        public function modify_password( $form, $post_id ) {
            $new_password = get_field( 'new_password' );

            wp_set_password( $new_password, get_current_user_id() );
        }

        /**
         * Validate lost password form's fields
         *
         * @param $form
         * @param $post_id
         * @param $alias
         */
        public function validate_lost_password_step_1( $form, $post_id, $alias ) {
            // Get user
            $current_user = get_field( 'email' );
            $current_user = get_user_by( 'email', $current_user );

            // Can't get user
            if ( !is_a( $current_user, 'WP_User' ) ) {
                acfe_add_validation_error( '', __( "Aucun utilisateur n'a pas été trouvé.", 'pilot-in' ) );
            }
        }

        /**
         * Fill User ID
         *
         * @param $field
         *
         * @return mixed
         */
        public function fill_user_id( $field ) {
            // Get reset key
            $reset_key = acf_maybe_get_GET( 'reset_key' );
            if ( !$reset_key ) {
                return $field;
            }

            // Get user ID
            $user_id = acf_maybe_get_GET( 'user' );
            if ( !$user_id ) {
                return $field;
            }

            // Decode user ID
            $user_id = base64_decode( $user_id );

            // Get transient key
            $transient     = get_transient( 'lost_pwd' );
            $transient_key = acf_maybe_get( $transient, $user_id );
            $transient_key = acf_maybe_get( $transient_key, 'reset_key' );

            // If reset key is ok, fill user ID
            if ( $transient_key === $reset_key ) {
                $field['value'] = $user_id;
            }

            return $field;
        }

        /**
         * Validate lost password form's fields
         *
         * @param $form
         * @param $post_id
         * @param $alias
         */
        public function validate_lost_password_step_2( $form, $post_id, $alias ) {
            // Get fields
            $new_password     = get_field( 'new_password' );
            $confirm_password = get_field( 'confirm_password' );
            $user_id          = get_field( 'lost_pwd_user_id' );

            // User ID is empty
            if ( !$user_id ) {
                acfe_add_validation_error( 'lost_pwd_user_id', __( 'Le lien de réinitialisation a expiré.', 'pilot-in' ) );
            }

            // Different passwords
            if ( $new_password !== $confirm_password ) {
                acfe_add_validation_error( 'new_password_confirmation', __( 'Les mots de passe sont différents.', 'pilot-in' ) );
            }
        }

        /**
         * Lost password - Step 1
         *
         * @param $form
         * @param $post_id
         */
        public function lost_password_step_1( $form, $post_id ) {
            // Get user
            $current_user = get_field( 'email' );
            $current_user = get_user_by( 'email', $current_user );

            // Get reset key
            $reset_key = uniqid();

            // Add user to transient
            $transient                      = get_transient( 'lost_pwd' );
            $transient                      = $transient ?: array();
            $transient[ $current_user->ID ] = array(
                'user_id'   => $current_user->ID,
                'reset_key' => $reset_key,
            );

            // Update transient for 1h
            set_transient( 'lost_pwd', $transient, 3600 );

            // Lost password URL
            $lost_pwd_page = pma_get_lost_password_page();
            $lost_pwd_url  = $lost_pwd_page ? add_query_arg(
                array(
                    'reset_key' => $reset_key,
                    'user'      => base64_encode( $current_user->ID ),
                ),
                get_permalink( $lost_pwd_page )
            ) : '';

            // Get mail data
            $email_content = pma_get_lost_pwd_1_mail();
            $object        = acf_maybe_get( $email_content, 'object' );
            $message       = acf_maybe_get( $email_content, 'message' );
            $message       = str_replace( '{change_pwd_url}', $lost_pwd_url, $message );

            // Send step 1 email
            wp_mail(
                $current_user->user_email,
                $object,
                $message,
                'Content-Type: text/html'
            );
        }

        /**
         * Lost password - Step 2
         *
         * @param $form
         * @param $post_id
         */
        public function lost_password_step_2( $form, $post_id ) {
            // Get fields
            $new_password = get_field( 'new_password' );
            $user_id      = get_field( 'lost_pwd_user_id' );
            $current_user = get_user_by( 'ID', $user_id );

            // Change password
            wp_set_password( $new_password, $user_id );

            // Get mail data
            $email_content = pma_get_lost_pwd_2_mail();
            $object        = acf_maybe_get( $email_content, 'object' );
            $message       = acf_maybe_get( $email_content, 'message' );

            // Send step 2 email
            wp_mail(
                $current_user->user_email,
                $object,
                $message,
                'Content-Type: text/html'
            );

            // Delete user data from transient
            $transient = get_transient( 'lost_pwd' );
            unset( $transient[ $user_id ] );

            // Update transient for 1h
            set_transient( 'lost_pwd', $transient, 3600 );
        }
    }

    // Instantiate
    new Pilotin_My_Account_Main();
}
