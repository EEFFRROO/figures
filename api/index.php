<?php

include __DIR__ . "/Application/Application.php";

function router($params) {
    $method = $params["method"];
    if ($method) {
        $app = new Application();
        switch ($method) {
            case "getFigures": return $app->getFigures();
            case "addCircle": return $app->addCircle($params);
            case "addTriangle": return $app->addTriangle($params);
            case "addParallelogram": return $app->addParallelogram($params);
            default: return false;
        }
    }

    return false;
}

function answer($data) {
    if ($data) {
        return array(
            "result" => "ok",
            "data" => $data
        );
    } 
    return array(
        "result" => "error", 
        "error" => array(
            "code" => 9000, 
            "text" => "unknown error"
        )
    );
}

echo json_encode(answer(router($_GET)));