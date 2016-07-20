<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 12/07/16
 * Time: 15:37
 */

namespace App\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController
{
    var $app;

    /**
     * returning array if method name of the controller as http request handler with http method as key
     * example: return array('get'=>array('mehod1', 'method2'), 'post'=>array(), 'put'=>array(), 'delete'=>array());
     * @return array
     */
    abstract function getRoutes();


    /**
     *
     * @return mixed
     * @deprecated use $app from first arg  ument
     */
    function getApp(){
        return $this->app;
    }
}