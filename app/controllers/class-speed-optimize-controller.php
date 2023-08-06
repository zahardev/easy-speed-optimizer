<?php

namespace Espdopt\Controllers;

use Espdopt\Interfaces\Singleton;
use Espdopt\Traits\Singleton as SingletonTrait;


/**
 * Class Speed_Optimize_Controller
 * @package Espdopt
 */
class Speed_Optimize_Controller implements Singleton {

    use SingletonTrait;

    protected $settings;

    /**
     * Init function
     */
    public function init() {
        if ( ! $this->need_optimization() ) {
            return;
        }

        $this->dequeue_styles();
        $this->dequeue_scripts();
        $this->init_critical_css();
        $this->disable_emoji();
    }

    protected function disable_emoji() {
        add_action( 'init', function () {
            if ( $this->get_option( 'main', 'remove_emoji' ) ) {
                remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
                remove_action( 'wp_print_styles', 'print_emoji_styles' );
            }
        } );
    }

    protected function need_optimization() {
        if ( is_admin() ) {
            return false;
        }

        // Now try checks for early calls:
        $admin_url   = get_admin_url();
        $current_url = home_url( add_query_arg( $_GET ) );

        $is_admin             = 0 === strpos( $current_url, $admin_url );
        $is_elementor_preview = isset( $_GET['elementor-preview'] );

        if ( $is_admin || $is_elementor_preview ) {
            return false;
        }

        return true;
    }

    protected function init_critical_css() {
        $critical_css = Settings_Controller::instance()->get_settings( 'critical_css' );

        if ( empty( $critical_css['enable_critical_css'] ) ) {
            return;
        }

        $this->maybe_defer_all_styles();
        $this->print_critical_css( $critical_css['critical_css'] );
    }

    protected function print_critical_css( $css ) {
        if ( empty( $css ) ) {
            return;
        }

        add_action( 'wp_print_scripts', function () use ( $css ) {
            echo '<!-- Easy Speed Optimizer Critical CSS -->' . PHP_EOL;
            echo '<style>' . $css . '</style>' . PHP_EOL;
            echo '<!-- End Easy Speed Optimizer Critical CSS -->' . PHP_EOL;
        }, 100 );
    }

    protected function maybe_defer_all_styles() {
        add_action( 'init', function () {
            if ( is_admin() ) {
                return;
            }

            global $wp;
            $wp->parse_request();
            if ( false !== strpos( $wp->request, 'wp-login.php' ) ) {
                return;
            }

            $this->defer_all_styles();
        } );
    }

    protected function defer_all_styles() {
        add_filter( 'style_loader_tag', function ( $link ) {

            if ( is_admin() ) {
                return $link;
            }

            $deferred = '<noscript>' . $link . '</noscript>';
            $deferred .= str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $link );

            return $deferred;
        } );
    }


    /**
     * Dequeue styles.
     * */
    protected function dequeue_styles() {
        add_action( 'wp_print_styles', function () {
            $style_ids = $this->get_option( 'main', 'remove_css' );

            $style_ids = explode( PHP_EOL, $style_ids );

            if ( empty( $style_ids ) ) {
                return;
            }

            foreach ( $style_ids as $style_id ) {
                $style_id = $this->strip_asset_id( $style_id );
                $this->dequeue_style( $style_id );

                // Also try to dequeue style if user specifies id with -css in the end.
                if ( '-css' === substr( $style_id, - 4, 4 ) ) {
                    $style_id = substr( $style_id, 0, strlen( $style_id ) - 4 );
                    $this->dequeue_style( $style_id );
                }
            }
        }, 0 );
    }

    /**
     * Dequeue scripts.
     * */
    protected function dequeue_scripts() {
        add_action( 'wp_print_styles', function () {
            $script_ids = $this->get_option( 'main', 'remove_js' );

            $script_ids = explode( PHP_EOL, $script_ids );

            if ( empty( $script_ids ) ) {
                return;
            }

            foreach ( $script_ids as $script_id ) {
                $script_id = $this->strip_asset_id( $script_id );
                $this->dequeue_script( $script_id );

                // Also try to dequeue script if user specifies id with -js in the end.
                if ( '-js' === substr( $script_id, - 3, 3 ) ) {
                    $script_id = substr( $script_id, 0, strlen( $script_id ) - 3 );
                    $this->dequeue_script( $script_id );
                }
            }
        }, 0 );
    }

    /**
     * @param string $asset_id
     */
    protected function strip_asset_id( $asset_id ) {
        return str_replace( array( "'", '"', ",", "\r" ), '', $asset_id );
    }


    protected function get_option( $tab, $option ) {
        $settings = $this->get_settings();

        return isset( $settings[ $tab ][ $option ] ) ? $settings[ $tab ][ $option ] : null;
    }

    protected function get_settings() {
        if ( empty( $this->settings ) ) {
            $this->settings = get_option( Settings_Controller::MANAGER_SETTINGS_URL );
        }

        return $this->settings;
    }

    public function dequeue_style( $style_id ) {
        global $wp_styles;

        foreach ( $wp_styles->registered as $dependency ) {
            /**
             * @var \_WP_Dependency $dependency
             * */
            if ( ! empty( $dependency->deps ) && in_array( $style_id, $dependency->deps, true ) ) {
                $key = array_search( $style_id, $dependency->deps );
                unset( $dependency->deps[ $key ] );
            }
        }

        wp_dequeue_style( $style_id );
        wp_deregister_style( $style_id );
    }


    public function dequeue_script( $script_id ) {
        global $wp_scripts;

        foreach ( $wp_scripts->registered as $dependency ) {
            /**
             * @var \_WP_Dependency $dependency
             * */
            if ( ! empty( $dependency->deps ) && in_array( $script_id, $dependency->deps, true ) ) {
                $key = array_search( $script_id, $dependency->deps );
                unset( $dependency->deps[ $key ] );
            }
        }

        wp_dequeue_script( $script_id );
        wp_deregister_script( $script_id );
    }
}
