<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/plain');

class Bob {
	private static $url    = '';
	private static $method = '';
	private static $routes = [];

	public static function add($methods, $pattern, $callback) {
		$methods = (is_array($methods)) ? $methods : [$methods];
		$methods = array_map('strtoupper', $methods);

		static::$routes[] = [
			'methods'  => $methods,
			'pattern'  => $pattern,
			'callback' => $callback
		];
	}

	public static function get($pattern, $callback) {
		static::add(['get'], $pattern, $callback);
	}

	public static function post($pattern, $callback) {
		static::add(['post'], $pattern, $callback);
	}

	private static function url_elements($url) {
		$elements = explode('/', trim(str_replace('//', '/', $url), '/'));
		$elements = static::trim_arr($elements);

		return $elements;
	}

	/**
	 * remove empty values
	 */
	private static function trim_arr($arr) {
		return array_filter($arr, function($var) {
			return !empty($var);
		});
	}

	/**
	 * look for a ":" or "!", says "yes" if the function exists
	 */
	private static function is_function($value, $function) {
		if($function[0] == ':')
			return function_exists(substr($function, 1)) and  call_user_func(substr($function, 1), $value);
		else if($function[0] == '!')
			return function_exists(substr($function, 1)) and !call_user_func(substr($function, 1), $value);
		else
			return false;
	}

	public static function go() {
		static::$url    = static::url_elements($_SERVER['REQUEST_URI']);
		static::$method = $_SERVER['REQUEST_METHOD'];

		if(static::$url[0] == 'Bob_static.php')
			array_shift(static::$url);

		foreach(static::$routes as $route)
			static::execute($route['methods'], $route['pattern'], $route['callback']);
	}

	private static function execute($methods, $pattern, $callback) {
		$arguments = [];
		$pattern   = static::url_elements($pattern);

		if(in_array(static::$method, $methods) and count($pattern) == count(static::$url)) {
			for($i = 0; $i < count($pattern); $i++)
				if(static::is_function(static::$url[$i], $pattern[$i]))
					$arguments[] = static::$url[$i];
				else if($pattern[$i] != static::$url[$i])
					return false;

			call_user_func_array($callback, $arguments);
		}

		return false;
	}
}

Bob::get('/', function() {
	echo 'Hello World';
});

Bob::get('/test', function() {
	echo 'test';
});

Bob::go();
?>
