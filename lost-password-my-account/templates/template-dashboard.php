<?php
// Check if user is allowed to be here
Pilotin_My_Account_Main::maybe_redirect_user();

// Header
get_header();

// Content
if ( function_exists( 'the_pip_content' ) ) {
    the_pip_content();
}

// Footer
get_footer();
