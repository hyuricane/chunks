<?php
$signaturecheck = function (array $list = array()) use ($app){
    return function () use ($list, $app){
        $params = $app->request->params();
        $signature = "";
        if (isset($params['signature'])){
            $signature = $params['signature'];
        }else{
            $app->response->json(array(
                "status"=>false,
                "error_code"=>"E001",
                "error_message"=>"Insuficient Parameters"
            ));
            $app->stop();
        }
        $text = "";
        foreach ($list as $key){
            if (isset($params[$key])){
                $text .= $params[$key];
            }else{
                $app->response->json(array(
                    "status"=>false,
                    "error_code"=>"E001",
                    "error_message"=>"Insuficient Parameters"
                ));
                $app->stop();
            }
        }
    };
};
?>