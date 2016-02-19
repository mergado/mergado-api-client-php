<?php

namespace MergadoClient;

interface Redirector {
	public static function redirect($location, $code);
}