<?php
/**
 * Plugin Name: Easy Speed Optimizer
 * Version: 1.1.1
 * Description: This plugin removes redundant scripts from the pages and provides possibility to setup above the fold critical CSS.
 * Text Domain: easy-speed-optimizer
 * Author: Sergey Zakharchenko
 * Author URI:  https://github.com/zahardev
 */

use Espdopt\App;

if ( ! function_exists( 'add_action' ) ) {
    exit;
}

define( 'ESPDOPT_PLUGIN_VERSION', '1.1.1' );
define( 'ESPDOPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ESPDOPT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'ESPDOPT_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require_once 'wp-autoloader.php';

if ( class_exists( 'Espdopt_Pro\App_Pro' ) ) {
    Espdopt_Pro\App_Pro::instance()->init();
} else {
    App::instance()->init();
}

