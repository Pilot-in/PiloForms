<?php
defined( 'ABSPATH' ) || exit;

/**
 * Include if file exists
 *
 * @param string $filename
 */
function pma_include( $filename = '' ) {
    $file_path = PMA_PATH . ltrim( $filename, '/' );
    if ( file_exists( $file_path ) ) {
        include_once( $file_path );
    }
}

/**
 * Check if Pilo'Press is activated
 */
add_action( 'after_plugin_row_' . PMA_BASENAME, 'pma_plugin_row', 5, 3 );
function pma_plugin_row( $plugin_file, $plugin_data, $status ) {

    // If Pilo'Press is activated, return
    if ( pma_init()->has_pip() ) {
        return;
    }

    ?>

    <style>
        .plugins tr[data-plugin='<?php echo PMA_BASENAME; ?>'] th,
        .plugins tr[data-plugin='<?php echo PMA_BASENAME; ?>'] td {
            box-shadow: none;
        }

        <?php if( isset( $plugin_data['update'] ) && !empty( $plugin_data['update'] ) ): ?>

        .plugins tr.pilotin-my-account-plugin-tr td {
            box-shadow: none !important;
        }

        .plugins tr.pilotin-my-account-plugin-tr .update-message {
            margin-bottom: 0;
        }

        <?php endif; ?>
    </style>

    <tr class="plugin-update-tr active pilotin-my-account-plugin-tr">
        <td colspan="3" class="plugin-update colspanchange">
            <div class="update-message notice inline notice-error notice-alt">
                <p><?php _e( "Pilot'in - My Account requires Pilo'Press.", 'pip-my-account' ); ?></p>
            </div>
        </td>
    </tr>

    <?php

}
