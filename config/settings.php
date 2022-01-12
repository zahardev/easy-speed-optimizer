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
            ),
            array(
                'id'          => 'remove_js',
                'type'        => 'textarea',
                'title'       => __( 'Remove JS', 'easy-speed-optimizer' ),
                'description' => __( 'Specify the JS script ids (each in new line) that you want to be removed from the pages.', 'easy-speed-optimizer' ),
            ),
        ),

    ),
    array(
        'id'     => 'critical_css',
        'title'  => 'Critical CSS',
        'fields' => array(
            array(
                'id'    => 'enable_critical_css',
                'type'  => 'checkbox',
                'title' => 'Enable above the fold critical CSS',
            ),
            array(
                'id'      => 'critical_css',
                'type'    => 'textarea',
                'title'   => 'Critical CSS',
                'classes' => 'espdopt_critical_css',
            ),
        ),
    ),
) );
