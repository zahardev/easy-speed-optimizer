<?php

namespace Espdopt\Controllers;

use Espdopt\Entities\Settings_Tab;
use Espdopt\Services\Renderer;

use Espdopt\Traits\Singleton as SingletonTrait;

/**
 * Class Settings_Controller
 * @package Espdopt
 */
class Settings_Controller {

    use SingletonTrait;

    const MANAGER_SETTINGS_URL = 'espdopt_settings';

    const DEFAULT_TAB = 'main';


    /**
     * @var array $settings
     */
    private $settings;


    public function init() {
        add_filter( 'plugin_action_links_' . ESPDOPT_PLUGIN_BASENAME, array( $this, 'add_plugin_links' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_pages' ) );
        add_action( 'admin_init', array( $this, 'init_setting_tabs' ) );
        add_action( 'espdopt_tab_settings', array( $this, 'provide_settings_tab' ) );

        return $this;
    }

    /**
     * @param Settings_Tab $tab
     */
    public function provide_settings_tab( $tab ) {
        $args = array(
            'id'    => 'espdopt_settings_tab',
            'value' => $tab->id,
        );

        $this->render_hidden( $args );
    }

    /**
     * @param array $links
     *
     * @return array
     */
    public function add_plugin_links( $links ) {
        $settings_link = Renderer::fetch( 'link', [
            'href'  => admin_url( 'options-general.php?page=' . self::MANAGER_SETTINGS_URL ),
            'label' => 'Settings',
        ] );

        array_unshift( $links, $settings_link );

        return $links;
    }

    public function add_settings_pages() {
        $pages = array(
            array(
                'title'     => __( 'Easy Speed Optimizer', 'easy-speed-optimizer' ),
                'menu_slug' => self::MANAGER_SETTINGS_URL,
                'page_slug' => self::MANAGER_SETTINGS_URL,
            ),
        );

        foreach ( $pages as $page ) {
            add_options_page(
                $page['title'],
                $page['title'],
                'manage_options',
                $page['menu_slug'],
                function () use ( $page ) {
                    $this->render_settings_page( $page['page_slug'] );
                }
            );
        }
    }

    public function init_setting_tabs() {

        if ( ! $this->is_plugin_settings_page() ) {
            return;
        }

        $current_tab_name = $this->get_current_tab();

        $tabs = $this->get_tabs();

        register_setting( self::MANAGER_SETTINGS_URL, self::MANAGER_SETTINGS_URL );

        $current_tab = $tabs[ $current_tab_name ];

        add_settings_section( self::MANAGER_SETTINGS_URL, '', null, self::MANAGER_SETTINGS_URL );

        foreach ( $current_tab->fields as $field ) {
            $settings = $this->get_settings( $current_tab_name );
            if ( isset( $settings[ $field['id'] ] ) ) {
                $val = $settings[ $field['id'] ];
            } else {
                $val = '';
            }

            $this->add_settings_field( $field, $val );
        }
    }

    protected function is_plugin_settings_page() {
        if ( self::MANAGER_SETTINGS_URL === filter_input( INPUT_GET, 'page' ) ||
             self::MANAGER_SETTINGS_URL === filter_input( INPUT_POST, 'option_page' )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $id
     * @param string $title
     * @param callable|null $callback
     */
    protected function add_settings_section( $id, $title, $callback = null ) {
        add_settings_section( $id, $title, $callback, self::MANAGER_SETTINGS_URL );
    }


    protected function add_settings_field( $args, $value ) {
        $field_id = $this->get_settings_field_id( $args['id'] );
        add_settings_field(
            $field_id,
            isset( $args['title'] ) ? $args['title'] : '',
            array( $this, 'render_' . $args['type'] ),
            self::MANAGER_SETTINGS_URL,
            self::MANAGER_SETTINGS_URL,
            array_merge( $args, array( 'value' => $value, 'id' => $field_id ) )
        );
    }

    protected function get_settings_field_id( $field_id ) {
        return sprintf( '%s[%s][%s]', self::MANAGER_SETTINGS_URL, $this->get_current_tab(), $field_id );
    }

    protected function get_current_tab() {
        $post_tab = filter_input( INPUT_POST, 'espdopt_settings_tab' );

        $tab = $post_tab ?: filter_input( INPUT_GET, 'tab' );

        return $tab ?: self::DEFAULT_TAB;
    }

    public function render_hidden( $args ) {
        Renderer::render( 'settings/fields/hidden', $args );
    }

    /**
     * @param array $args
     */
    public function render_checkbox( $args ) {
        Renderer::render( 'settings/fields/checkbox', $args );
    }

    /**
     * @param array $args
     */
    public function render_text( $args ) {
        $defaults = array(
            'id'          => '',
            'title'       => '',
            'placeholder' => '',
        );

        Renderer::render( 'settings/fields/text', wp_parse_args( $args, $defaults ) );
    }

    /**
     * @param array $args
     */
    public function render_textarea( $args ) {
        $defaults = array(
            'id'    => '',
            'title' => '',
            'label' => '',
            'rows'  => '',
            'cols'  => '',
        );

        Renderer::render( 'settings/fields/textarea', wp_parse_args( $args, $defaults ) );
    }

    /**
     * @return array|mixed
     */
    public function get_settings( $tab = '' ) {
        $tab = $tab ?: $this->get_current_tab();
        if ( isset( $this->settings[ $tab ] ) ) {
            return $this->settings[ $tab ];
        }
        $settings = get_option( self::MANAGER_SETTINGS_URL );

        $this->settings = $settings;

        return $this->settings[ $tab ];
    }


    /**
     * @param string $tab
     *
     * @return string
     */
    public function get_section_id( $tab = '' ) {
        $tab = $tab ?: $this->get_current_tab();

        return self::MANAGER_SETTINGS_URL . '_' . $tab;
    }

    /**
     * @param null $post_type
     *
     * @return array
     */
    public function get_post_settings( $post_type = null ) {
        $settings = $this->get_settings();

        if ( empty( $post_type ) ) {
            $current_screen = get_current_screen();
            $post_type      = $current_screen->post_type;
        }

        if ( empty( $settings[ $post_type ] ) ) {
            return [];
        }

        return $settings[ $post_type ];
    }


    /**
     * @return Settings_Tab[]
     */
    protected function get_tabs() {
        $tabs        = $this->get_tab_settings();
        $tab_objects = array();

        foreach ( $tabs as $tab ) {
            $tab_objects[ $tab['id'] ] = new Settings_Tab( $tab );
        }

        return $tab_objects;
    }


    /**
     * @return array
     */
    protected function get_tab_settings() {
        return require ESPDOPT_PLUGIN_DIR . '/config/settings.php';
    }


    /**
     * @param string $page_slug
     */
    public function render_settings_page( $page_slug ) {
        $tabs        = $this->get_tabs();
        $current_tab = $this->get_current_tab();

        $current_tab = $tabs[ $current_tab ];

        Renderer::render( 'settings/settings.php', compact( 'page_slug', 'tabs', 'current_tab' ) );
    }
}
