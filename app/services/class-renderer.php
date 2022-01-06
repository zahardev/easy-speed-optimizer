<?php

namespace Espdopt\Services;

use Espdopt\Interfaces\Renderer as Interface_Renderer;

class Renderer implements Interface_Renderer {

    public static function fetch( $template, $args = [] ) {
        extract( $args );
        $filetype = wp_check_filetype( $template, [
            'php'  => 'text/html',
            'html' => 'text/html',
        ] );
        if ( false === $filetype['ext'] ) {
            $template .= '.php';
        }
        ob_start();
        $path = ESPDOPT_PLUGIN_DIR . 'templates/' . $template;
        include $path;
        $res = ob_get_clean();

        return $res;
    }

    public static function render( $template, $args = [] ) {
        echo self::fetch( $template, $args );
    }
}
