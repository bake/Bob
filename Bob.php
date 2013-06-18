<?php
class Bob {
	private $url    = '';
	private $method = '';
	private $routes = [];

	function __construct() {
		$this->url    = $this->url_elements($_SERVER['REQUEST_URI']);
		$this->method = $_SERVER['REQUEST_METHOD'];

		if($this->url[0] == 'Bob.php')
			array_shift($this->url);
	}

	private function url_elements($url) {
		return explode('/', trim(str_replace('//', '/', $url), '/'));
	}

	public function go() {
		foreach($this->routes as $route)
			$this->execute($route['methods'], $route['pattern'], $route['callback']);
	}

	private function execute($methods, $pattern, $callback) {
		$arguments = [];
		$pattern   = $this->url_elements($pattern);

		if(in_array($this->method, $methods) and count($pattern) == count($this->url)) {
			for($i = 0; $i < count($pattern); $i++)
				if($this->is_function($this->url[$i], $pattern[$i]))
					$arguments[] = $this->url[$i];
				else if($pattern[$i] != $this->url[$i])
					return false;

			call_user_func_array($callback, $arguments);
		}

		return false;
	}

	private function is_equal($a, $b) {
		return ($a == $b);
	}

	private function is_function($value, $function) {
		if($function[0] == ':')
			return function_exists(substr($function, 1)) and  call_user_func(substr($function, 1), $value);
		else if($function[0] == '!')
			return function_exists(substr($function, 1)) and !call_user_func(substr($function, 1), $value);
		else return false;
	}

	public function add($methods, $pattern, $callback) {
		$methods = (is_array($methods)) ? $methods : [$methods];
		$methods = array_map('strtoupper', $methods);

		$this->routes[] = [
			'methods'  => $methods,
			'pattern'  => $pattern,
			'callback' => $callback
		];
	}

	public function get($pattern, $callback) {
		$this->add(['get'], $pattern, $callback);
	}

	public function post($pattern, $callback) {
		$this->add(['post'], $pattern, $callback);
	}
}
?>
