<?php

/**
 * Get dashboard menu name
 *
 * @return mixed
 */
function pma_get_dashboard_menu() {
    return get_field( 'dashboard_menu', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get login page
 *
 * @return mixed
 */
function pma_get_login_page() {
    return get_field( 'login_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get register page
 *
 * @return mixed
 */
function pma_get_register_page() {
    return get_field( 'register_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get lost password page
 *
 * @return mixed
 */
function pma_get_lost_password_page() {
    return get_field( 'lost_password_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get shop page
 *
 * @return mixed
 */
function pma_get_shop_page() {
    return get_field( 'shop_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get cart page
 *
 * @return mixed
 */
function pma_get_cart_page() {
    return get_field( 'cart_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get checkout page
 *
 * @return mixed
 */
function pma_get_checkout_page() {
    return get_field( 'checkout_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get thank you page
 *
 * @return mixed
 */
function pma_get_thank_you_page() {
    return get_field( 'thank_you_page', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get redirect page when user is not logged in
 *
 * @return mixed
 */
function pma_get_logged_out_redirect_page() {
    return get_field( 'not_logged_in_redirect', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get lost password email content for step 1
 *
 * @return mixed
 */
function pma_get_lost_pwd_1_mail() {
    return get_field( 'lost_password_step_1', Pilotin_My_Account_Main::$options_id );
}

/**
 * Get lost password email content for step 2
 *
 * @return mixed
 */
function pma_get_lost_pwd_2_mail() {
    return get_field( 'lost_password_step_2', Pilotin_My_Account_Main::$options_id );
}
