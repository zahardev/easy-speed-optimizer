<?php

return apply_filters( 'espdopt_settings_config', array(
    array(
        'id'     => 'main',
        'title'  => 'Main Settings',
        'fields' => array(
            array(
                'id'          => 'remove_css',
                'type'        => 'textarea',
                'title'       => __( 'Remove CSS', 'easy-speed-optimizer' ),
                'description' => __( 'Specify the style ids (each in new line) that you want to be removed from the pages.', 'easy-speed-optimizer' ),
                'rows'        => 10,
                'cols'        => 60,
            ),
            array(
                'id'          => 'remove_js',
                'type'        => 'textarea',
                'title'       => __( 'Remove JS', 'easy-speed-optimizer' ),
                'description' => __( 'Specify the JS script ids (each in new line) that you want to be removed from the pages.', 'easy-speed-optimizer' ),
                'rows'        => 10,
                'cols'        => 60,
            ),
        ),

    ),
) );
