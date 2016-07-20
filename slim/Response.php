<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 18/07/16
 * Time: 19:27
 */

namespace App;


use Slim\Http\Response as BaseResponse;

class Response extends BaseResponse
{
    public function json($body = array(), $httpCode = 200){
        $this->header("Content-Type", "application/json");
        $this->setStatus($httpCode);
        $this->setBody(json_encode($body));
        return $this;
    }
}