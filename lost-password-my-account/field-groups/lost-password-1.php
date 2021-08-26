<?php
if ( function_exists( 'acf_add_local_field_group' ) ) {

    acf_add_local_field_group(
        array(
            'key'                   => 'group_lost_pwd_1',
            'title'                 => __( 'Formulaire : Mot de passe oublié (1/2)', 'pilot-in' ),
            'fields'                => array(
                array(
                    'key'                        => 'field_email',
                    'label'                      => __( 'Adresse email', 'pilot-in' ),
                    'name'                       => 'email',
                    'type'                       => 'email',
                    'instructions'               => '',
                    'required'                   => 1,
                    'conditional_logic'          => 0,
                    'wrapper'                    => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'required_message'           => __( 'La valeur est requise', 'pilot-in' ),
                    'acfe_save_meta'             => 0,
                    'default_value'              => '',
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