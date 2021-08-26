<?php
if ( function_exists( 'acf_add_local_field_group' ) ) {

    acf_add_local_field_group(
        array(
            'key'                   => 'group_lost_pwd_2',
            'title'                 => __( 'Formulaire : Mot de passe oublié (2/2)', 'pilot-in' ),
            'fields'                => array(
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
                    'required_message'           => '',
                    'placeholder'                => '',
                    'prepend'                    => '',
                    'append'                     => '',
                    'acfe_field_group_condition' => 0,
                ),
                array(
                    'key'                        => 'field_confirm_password',
                    'label'                      => __( 'Confirmer le mot de passe', 'pilot-in' ),
                    'name'                       => 'confirm_password',
                    'type'                       => 'password',
                    'instructions'               => '',
                    'required'                   => 1,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'required_message'           => '',
                    'placeholder'                => '',
                    'prepend'                    => '',
                    'append'                     => '',
                    'acfe_field_group_condition' => 0,
                ),
                array(
                    'key'                        => 'field_lost_pwd_user_id',
                    'label'                      => __( 'User ID', 'pilot-in' ),
                    'name'                       => 'lost_pwd_user_id',
                    'type'                       => 'acfe_hidden',
                    'instructions'               => '',
                    'required'                   => 0,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'acfe_save_meta'             => 0,
                    'default_value'              => '',
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