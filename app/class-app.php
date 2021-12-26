<?php

namespace ESPDOPT;

use ESPDOPT\Controllers\Frontend_Controller;
use ESPDOPT\Controllers\Speed_Optimize_Controller;
use ESPDOPT\Controllers\User_Assets_Controller;
use ESPDOPT\Controllers\Settings_Controller;
use ESPDOPT\Interfaces\Singleton;
use ESPDOPT\Traits\Singleton as SingletonTrait;


/**
 * Class App
 */
class App implements Singleton {

	use SingletonTrait;

	/**
	 * Init function
	 */
    public function init() {
        if ( is_admin() ) {
            Settings_Controller::instance()->init();
            Frontend_Controller::instance()->init();
        } else {
            Speed_Optimize_Controller::instance()->init();
        }
    }
}
