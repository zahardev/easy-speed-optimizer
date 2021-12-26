<?php

namespace ESPDOPT\Interfaces;

interface Singleton {
	public function init();

	public static function instance();
}
