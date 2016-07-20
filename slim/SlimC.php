<?php

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 12/07/16
 * Time: 15:08
 */

namespace App;
use App\Controller\BaseController;

/**
 * Class SlimC
 * @property \App\Response $response
 */
class SlimC extends \Slim\Slim{
    protected $_baseUrl;

    /**
     * SlimC constructor.
     * @param $_baseUrl
     */
    public function __construct(array $userSettings = array())
    {
        parent::__construct($userSettings);
        $this->container->singleton('response', function ($c) {
            return new Response();
        });
    }


    public function baseUrl($route = '') {
        return $this->_baseUrl.$route;
    }

    public function setBaseUrl($base_url) {
        $this->_baseUrl = $base_url;
    }

    /**
     * Get application instance by name
     * @param  string    $name The name of the Slim application
     * @return \App\SlimC|null
     */
    public static function getInstance($name = 'default')
    {
        return isset(static::$apps[$name]) ? static::$apps[$name] : null;
    }

    public function registerController ($controllerPath, BaseController $controller){
        $app = $this;

        $controller->app = $this;
        $methods = array('post', 'get', 'put', 'delete');
        $routes = $controller->getRoutes();

        foreach ($methods as $httpMethod){
            if (isset($routes[$httpMethod]) && is_array($routes[$httpMethod])){
                foreach ($routes[$httpMethod] as $key => $value) {
                    $middleWares = array();
                    $controllerMethod = "/index";
                    if (is_int($key)){
                        if (is_array($value)){
                            $controllerMethod = array_shift($value);
                            $path = "/$controllerMethod";
                            $middleWares = $value;
                        }else{
                            $controllerMethod = $value;
                            $path = $value;
                        }
                    }else{
                        $path = "/$key";
                        if (is_array($value)){
                            $controllerMethod = array_shift($value);
                            $middleWares = $value;
                        }else{
                            $controllerMethod = $value;
                        }
                    }
                    $path = '/' . $controllerPath .'/'. $path;
                    $path = preg_replace('~/+~', '/', $path);

                    $callable = get_class($controller) . "::$controllerMethod";

//                    $app->log->info(array($path, $callable));

                    if (is_callable($callable)){
                        call_user_func_array(array($this, $httpMethod), array_merge(
                            array($path),
                            $middleWares,
                            array(
                                function () use ($app, $controller, $controllerMethod){
                                    $args = array($app);
                                    $args = array_merge($args, func_get_args());
                                    call_user_func_array(array($controller, $controllerMethod), $args);
                                }
                            )
                        ));
                    }

                }
            }
        }
    }

//    public function registerController($path, BaseController $controller){
//        $controller->app = $this;
//        $app = $this;
//        $this->group($path, function () use($app, $controller){
//            $routes = array_merge(array(), $controller->getRoutes());
//            $methods = array('post', 'get', 'put', 'delete');
//
//            foreach ($methods as $httpMethod) {
//                if (isset($routes[$httpMethod]) && is_array($routes[$httpMethod])){
//                    foreach ($routes[$httpMethod] as $key=>$value){
//                        $middleWares = array();
//                        $controllerMethod = "index";
//
//                        if (is_int($key)){
//                            if (is_array($value)){
//                                $controllerMethod = array_shift($value);
//                                $path = "/$controllerMethod";
//                                $middleWares = $value;
//                            }else{
//                                $controllerMethod = $value;
//                                $path = $value;
//                            }
//                        }else{
//                            $path = "/$key";
//                            if (is_array($value)){
//                                $controllerMethod = array_shift($value);
//                                $middleWares = $value;
//                            }else{
//                                $controllerMethod = $value;
//                            }
//                        }
//
//                        if (starts_with($path, "//")) $path = substr($path, 1);
//
//                        $callable = get_class($controller) . "::$controllerMethod";
////                        $app->log->info(array($path, $callable));
//                        if (is_callable($callable)){
//                            call_user_func_array(array($app, $httpMethod), array_merge(
//                                array($path),
//                                $middleWares,
//                                array(
//                                    function () use ($app, $controller, $controllerMethod){
//                                        $args = array($app);
//                                        $args = array_merge($args, func_get_args());
//                                        call_user_func_array(array($controller, $controllerMethod), $args);
//                                    }
//                                )
//                            ));
//                        }
//
//                    }
//                }
//            }
//        });
//
//    }
}