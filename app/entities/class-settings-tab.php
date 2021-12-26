<?php

namespace ESPDOPT\Entities;

use ESPDOPT\Controllers\Settings_Controller;

/**
 * Class Settings_Tab
 * @package ESPDOPT
 */
class Settings_Tab {

    /**
     * string @var
     */
    public $id;

    /**
     * string @var
     */
    public $title;

    /**
     * string @var
     */
    public $description;

    /**
     * array @var
     */
    public $fields;

    /**
     * Field constructor.
     */
    public function __construct( $args ) {
        $this->id          = $args['id'];
        $this->title       = $args['title'];
        $this->description = $this->get_array_val( $args, 'description' );
        $this->fields      = $this->get_array_val( $args, 'fields', array() );
    }

    protected function get_array_val( $array, $key, $default = null ) {
        return isset( $array[ $key ] ) ? $array[ $key ] : $default;
    }

    /**
     * @return string|void
     */
    public function get_url() {
        $url = admin_url( sprintf( 'options-general.php?page=%s', Settings_Controller::MANAGER_SETTINGS_URL ) );
        if ( Settings_Controller::DEFAULT_TAB === $this->id ) {
            return $url;
        }

        return add_query_arg( 'tab', $this->id, $url );
    }
}
