<?php
/**
 * Plugin Name: Easy Speed Optimizer
 * Version: 1.0.1
 * Description: This plugin removes redundant scripts from the pages and optimizes them.
 * Text Domain: easy-speed-optimizer
 * Author: Sergey Zakharchenko
 * Author URI:  https://github.com/zahardev
 */

use Espdopt\App;

if ( ! function_exists( 'add_action' ) ) {
    exit;
}

define( 'ESPDOPT_PLUGIN_VERSION', '1.0.1' );
define( 'ESPDOPT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ESPDOPT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'ESPDOPT_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require_once 'wp-autoloader.php';

App::instance()->init();
