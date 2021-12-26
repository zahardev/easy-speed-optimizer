<?php

namespace ESPDOPT\Controllers;

use ESPDOPT\Traits\Singleton;

/**
 * Class Assets_Controller
 * @package ESPDOPT
 */
class Frontend_Controller {

    use Singleton;

    public function init() {
        add_action( 'admin_init', array( $this, 'enqueue_assets' ) );

        return $this;
    }


    public function enqueue_assets() {
        $css_path = '/assets/css/espdopt.css';
        wp_enqueue_style(
            'espdopt-css',
            ESPDOPT_PLUGIN_URL . $css_path,
            [],
            ESPDOPT_PLUGIN_VERSION
        );
    }
}
