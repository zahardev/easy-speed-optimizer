<?php

namespace Espdopt;

use Espdopt\Controllers\Frontend_Controller;
use Espdopt\Controllers\Speed_Optimize_Controller;
use Espdopt\Controllers\Settings_Controller;
use Espdopt\Interfaces\Singleton;
use Espdopt\Traits\Singleton as SingletonTrait;


/**
 * Class App
 */
class App implements Singleton {

    use SingletonTrait;

    /**
     * Init function
     */
    public function init() {
        do_action( 'espdopt_before_init' );
        $this->init_controllers();
        do_action( 'espdopt_after_init' );
    }

    protected function init_controllers() {
        if ( is_admin() ) {
            Settings_Controller::instance()->init();
            Frontend_Controller::instance()->init();
        } else {
            Speed_Optimize_Controller::instance()->init();
        }
    }
}
