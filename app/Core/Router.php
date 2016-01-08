<?php

/**
 * Router - routing urls to closures and controllers - modified from https://github.com/NoahBuscher/Macaw
 *
 */
namespace Core;

use Core\View;

/**
 * Router class will load requested controller / closure based on url.
 */
class Router {

	/**
	 * Fallback for auto dispatching feature.
	 *
	 * @var boolean $fallback
	 */
	public static $fallback = true;

	/**
	 * If true - do not process other routes when match is found
	 *
	 * @var boolean $halts
	 */
	public static $halts = true;

	/**
	 * Array of routes
	 *
	 * @var array $routes
	 */
	public static $routes = [ ];

	/**
	 * Array of methods
	 *
	 * @var array $methods
	 */
	public static $methods = [ ];

	/**
	 * Array of callbacks
	 *
	 * @var array $callbacks
	 */
	public static $callbacks = [ ];

	/**
	 * Set an error callback
	 *
	 * @var null $errorCallback
	 */
	public static $errorCallback;

	/**
	 * Set route patterns
	 */
	public static $patterns = array (':any' => '[^/]+',':num' => '-?[0-9]+',':all' => '.*',':hex' => '[[:xdigit:]]+',':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}');

	/**
	 * Defines a route with or without callback and method.
	 *
	 * @param string $method        	
	 * @param
	 *        	array @params
	 */
	public static function __callstatic($method, $params) {
		$uri = dirname($_SERVER['PHP_SELF']) . '/' . $params[0];
		$callback = $params[1];
		
		array_push(self::$routes, $uri);
		array_push(self::$methods, strtoupper($method));
		array_push(self::$callbacks, $callback);
	}

	/**
	 * Add routes in bulk as an array
	 *
	 * @param array $routesList        	
	 */
	public static function addRoutes($routesList = []) {
		foreach ($routesList as $route => $callback) {
			array_push(self::$routes, dirname($_SERVER['PHP_SELF']) . '/' . $route);
			array_push(self::$methods, 'ANY');
			array_push(self::$callbacks, $callback);
		}
	}

	/**
	 * Defines callback if route is not found.
	 *
	 * @param string $callback        	
	 */
	public static function error($callback) {
		self::$errorCallback = $callback;
	}

	/**
	 * Don't load any further routes on match.
	 *
	 * @param boolean $flag        	
	 */
	public static function haltOnMatch($flag = true) {
		self::$halts = $flag;
	}

	/**
	 * Call object and instantiate.
	 *
	 * @param object $callback        	
	 * @param array $matched
	 *        	array of matched parameters
	 * @param string $msg        	
	 */
	public static function invokeObject($callback, $matched = null, $msg = null) {
		$last = explode('/', $callback);
		$last = end($last);
		
		$segments = explode('@', $last);
		
		$controller = $segments[0];
		$method = $segments[1];
		
		$controller = new $controller($msg);
		
		call_user_func_array([ $controller,$method], $matched ? $matched : [ ]);
	}

	/**
	 * autoDispatch by Volter9.
	 *
	 * Ability to call controllers in their controller/model/param way.
	 */
	public static function autoDispatch() {
		$uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
		$uri = '/' . $uri;
		if (strpos($uri, DIR) === 0) {
			$uri = substr($uri, strlen(DIR));
		}
		$uri = trim($uri, ' /');
		$uri = ($amp = strpos($uri, '&')) !== false ? substr($uri, 0, $amp) : $uri;
		$parts = explode('/', $uri);
		$controller = array_shift($parts);
		$controller = ! empty($controller) ? $controller : DEFAULT_CONTROLLER;
		$controller = str_replace(' ', '_', ucwords(str_replace('-', ' ', $controller)));
		
		// Check for file in top Controllers folder
		if (file_exists(SMVC . "app/Controllers/$controller.php")) {
			$controller = "\Controllers\\$controller";
		} else {
			// check whether there is any module with that name
			if (file_exists(SMVC . "app/Modules/$controller")) {
				$moduleName = $controller;
				
				$moduleController = array_shift($parts);
				$moduleController = ! empty($moduleController) ? $moduleController : DEFAULT_CONTROLLER;
				$moduleController = str_replace(' ', '_', ucwords(str_replace('-', ' ', $moduleController)));
				// die($moduleController);
				
				if (file_exists(SMVC . "app/Modules/$moduleName/Controllers/$moduleController.php")) {
					$controller = "\Modules\\$moduleName\\Controllers\\$moduleController";
				} else
					return false;
			} else {
				
				// check in sub folder beneath Contollers folder
				$subFolderName = $controller;
				
				$controller = array_shift($parts);
				$controller = ! empty($controller) ? $controller : DEFAULT_CONTROLLER;
				$controller = str_replace(' ', '_', ucwords(str_replace('-', ' ', $controller)));
				
				if (file_exists(SMVC . "app/Controllers/$subFolderName/$controller.php")) {
					$controller = "\Controllers\\$subFolderName\\$controller";
				} else
					return false;
			}
		}
		
		$method = array_shift($parts);
		$method = ! empty($method) ? $method : DEFAULT_METHOD;
		$args = ! empty($parts) ? $parts : [ ];
		
		$c = new $controller();
		if (method_exists($c, $method)) {
			call_user_func_array([ $c,$method], $args);
			// found method so stop
			return true;
		}
		return false;
	}

	/**
	 * Runs the callback for the given request.
	 */
	public static function dispatch() {
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$method = $_SERVER['REQUEST_METHOD'];
		
		$searches = array_keys(static::$patterns);
		$replaces = array_values(static::$patterns);
		
		self::$routes = str_replace('//', '/', self::$routes);
		
		$found_route = false;
		
		// parse query parameters
		
		$query = '';
		$q_arr = [ ];
		if (strpos($uri, '&') > 0) {
			$query = substr($uri, strpos($uri, '&') + 1);
			$uri = substr($uri, 0, strpos($uri, '&'));
			$q_arr = explode('&', $query);
			foreach ($q_arr as $q) {
				$qobj = explode('=', $q);
				$q_arr[] = array ($qobj[0] => $qobj[1]);
				if (! isset($_GET[$qobj[0]])) {
					$_GET[$qobj[0]] = $qobj[1];
				}
			}
		}
		
		// check if route is defined without regex
		if (in_array($uri, self::$routes)) {
			$route_pos = array_keys(self::$routes, $uri);
			
			// foreach route position
			foreach ($route_pos as $route) {
				if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
					$found_route = true;
					
					// if route is not an object
					if (! is_object(self::$callbacks[$route])) {
						// call object controller and method
						self::invokeObject(self::$callbacks[$route]);
						if (self::$halts) {
							return;
						}
					} else {
						// call closure
						call_user_func(self::$callbacks[$route]);
						if (self::$halts) {
							return;
						}
					}
				}
			}
			// end foreach
		} else {
			// check if defined with regex
			$pos = 0;
			
			// foreach routes
			foreach (self::$routes as $route) {
				$route = str_replace('//', '/', $route);
				
				if (strpos($route, ':') !== false) {
					$route = str_replace($searches, $replaces, $route);
				}
				
				if (preg_match('#^' . $route . '$#', $uri, $matched)) {
					if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
						$found_route = true;
						
						// remove $matched[0] as [1] is the first parameter.
						array_shift($matched);
						
						if (! is_object(self::$callbacks[$pos])) {
							// call object controller and method
							self::invokeObject(self::$callbacks[$pos], $matched);
							if (self::$halts) {
								return;
							}
						} else {
							// call closure
							call_user_func_array(self::$callbacks[$pos], $matched);
							if (self::$halts) {
								return;
							}
						}
					}
				}
				$pos ++;
			}
			// end foreach
		}
		
		if (self::$fallback) {
			// call the auto dispatch method
			$found_route = self::autoDispatch();
		}
		
		// run the error callback if the route was not found
		if (! $found_route) {
			if (! self::$errorCallback) {
				self::$errorCallback = function () {
					header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
					
					$data['title'] = '404';
					$data['error'] = "Oops! Page not found..";
					
					View::renderTemplate('header', $data);
					View::render('Error/404', $data);
					View::renderTemplate('footer', $data);
				};
			}
			
			if (! is_object(self::$errorCallback)) {
				// call object controller and method
				self::invokeObject(self::$errorCallback, null, 'No routes found.');
				if (self::$halts) {
					return;
				}
			} else {
				call_user_func(self::$errorCallback);
				if (self::$halts) {
					return;
				}
			}
		}
	}
}
