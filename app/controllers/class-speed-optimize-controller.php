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
        if ( is_admin() ) {
            return;
        }

        $this->dequeue_styles();
        $this->dequeue_scripts();
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
