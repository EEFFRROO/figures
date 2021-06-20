<?php 

include __DIR__ . '/../DB/DB.php';

spl_autoload_register(function ($class_name) {
    include __DIR__ . '/../Figures/' . $class_name . '.php';
});

class Application {
    public function __construct() {
        $this->db = new DB();
    }

    public function getFigures() {
        $params = $this->db->getParams();
        $prevId = 0;
        $result = [];
        $temp = [];
        // Распределение параметров по их фигурам
        while ($data = $params->fetchArray(SQLITE3_ASSOC)) {
            $id = $data["id"];
            $data = array_diff_key($data, ["id" => 0]); // Убираем id из результата
            if ($prevId == $id) {
                array_push($temp, $data);
            } else {
                if ($temp) {
                    $temp = $this->calcArea($temp);
                    // return $temp;
                    array_push($result, $temp);
                }
                $temp = [];
                array_push($temp, $data);
            }
            $prevId = $id;
        }
        if ($temp) {
            $temp = $this->calcArea($temp);
            array_push($result, $temp);
        }
        return $result;
    }

    private function calcArea($data) {
        switch ($data[0]["figureType"]) {
            case 'circle':
                $circle = new Circle($data[0]["x"], $data[0]["y"], $data[1]["x"], $data[1]["y"]);
                array_push($data, $circle->calcArea());
                return $data;
            case 'triangle':
                $triangle = new Triangle($data[0]["x"], $data[0]["y"], $data[1]["x"], $data[1]["y"], $data[2]["x"], $data[2]["y"]);
                array_push($data, $triangle->calcArea());
                return $data;
            case 'parallelogram':
                $parallelogram = new Parallelogram($data[0]["x"], $data[0]["y"], $data[1]["x"], $data[1]["y"], $data[2]["x"], $data[2]["y"]);
                array_push($data, $parallelogram->calcArea());
                if ($parallelogram->checkSquare()) // Если квадрат
                    array_push($data, "square");
                else 
                    array_push($data, "not square");
                return $data;
            default:
                return '?????';
                break;
        }
    }

    public function addCircle($params) {
        if ($params) {
            $this->db->addCircle($params["xc"], $params["yc"], $params["xr"], $params["yr"]);
            return true;
        }
        return false;
    }

    public function addTriangle($params) {
        if ($params) {
            $this->db->addTriangle($params["x1"], $params["y1"], $params["x2"], $params["y2"], $params["x3"], $params["y3"]);
            return true;
        }
        return false;
    }

    public function addParallelogram($params) {
        if ($params) {
            $this->db->addParallelogram($params["x1"], $params["y1"], $params["x2"], $params["y2"], $params["x3"], $params["y3"]);
            return true;
        }
        return false;
    }

}