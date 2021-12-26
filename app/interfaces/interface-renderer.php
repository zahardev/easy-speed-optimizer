<?php

namespace Espdopt\Interfaces;

interface Renderer {
    public static function fetch( $template, $args = [] );
    public static function render( $template, $args = [] );
}
