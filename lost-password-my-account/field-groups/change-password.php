<?php
if ( function_exists( 'acf_add_local_field_group' ) ) {

    acf_add_local_field_group(
        array(
            'key'                   => 'group_change_password',
            'title'                 => __( 'Formulaire : Changement mot de passe', 'pilot-in' ),
            'fields'                => array(
                array(
                    'key'                        => 'field_current_password',
                    'label'                      => __( 'Mot de passe actuel', 'pilot-in' ),
                    'name'                       => 'current_password',
                    'type'                       => 'password',
                    'instructions'               => '',
                    'required'                   => 1,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'required_message'           => __( 'La valeur est requise', 'pilot-in' ),
                    'placeholder'                => '',
                    'prepend'                    => '',
                    'append'                     => '',
                    'acfe_field_group_condition' => 0,
                ),
                array(
                    'key'                        => 'field_new_password',
                    'label'                      => __( 'Nouveau mot de passe', 'pilot-in' ),
                    'name'                       => 'new_password',
                    'type'                       => 'password',
                    'instructions'               => '',
                    'required'                   => 1,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'required_message'           => __( 'La valeur est requise', 'pilot-in' ),
                    'placeholder'                => '',
                    'prepend'                    => '',
                    'append'                     => '',
                    'acfe_field_group_condition' => 0,
                ),
                array(
                    'key'                        => 'field_new_password_confirmation',
                    'label'                      => __( 'Confirmer le nouveau mot de passe', 'pilot-in' ),
                    'name'                       => 'new_password_confirmation',
                    'type'                       => 'password',
                    'instructions'               => '',
                    'required'                   => 1,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'required_message'           => __( 'La valeur est requise', 'pilot-in' ),
                    'placeholder'                => '',
                    'prepend'                    => '',
                    'append'                     => '',
                    'acfe_field_group_condition' => 0,
                ),
            ),
            'location'              => array(
                array(
                    array(
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'all',
                    ),
                ),
            ),
            'menu_order'            => 0,
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'left',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => false,
            'description'           => '',
            'acfe_autosync'         => array(),
            'acfe_form'             => 0,
            'acfe_display_title'    => '',
            'acfe_meta'             => '',
            'acfe_note'             => '',
        )
    );

}
