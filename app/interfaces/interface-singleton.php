<?php

namespace Espdopt\Interfaces;

interface Singleton {
	public function init();

	public static function instance();
}
