<?php

spl_autoload_register(function ($class_name) {
    include __DIR__ . $class_name . '.php';
});

class Parallelogram extends Figure {

    public function __construct($x1, $y1, $x2, $y2, $x3, $y3) {
        $this->point1 = new Point($x1, $y1);
        $this->point2 = new Point($x2, $y2);
        $this->point3 = new Point($x3, $y3);
    }

    public function calcArea() {
        // s = a * h
        $a = $this->point1->calcDistance($this->point2);
        // Уравнение прямой Cx+Dy+E=0  === (y1-y2)x+(x2-x1)y+(x1y2-x2y1)=0
        $c = $this->point1->y - $this->point2->y;
        $d = $this->point2->x - $this->point1->x;
        $e = $this->point1->x * $this->point2->y - $this->point2->x * $this->point1->y;
        // h = |c*x+d*y+e|/sqrt(c^2+d^2)
        $h = abs($c * $this->point3->x + $d * $this->point3->y + $e) / sqrt($c * $c + $d * $d);
        $result = $a * $h;
        return $result;
    }

    public function checkSquare() { // Проверка на квадрат
        $firstDist = $this->point1->squareOfDistance($this->point2);
        $secondDist = $this->point2->squareOfDistance($this->point3);
        $thirdDist = $this->point3->squareOfDistance($this->point1);
        $diagonal = $this->bigger($firstDist, $this->bigger($secondDist, $thirdDist));
        if ($side = $this->equal($firstDist, $secondDist, $thirdDist)) {
            if ($diagonal == $side * 2)
                return true;
        }
        return false;
    }

    private function bigger($a, $b) {
        if ($a > $b)
            return $a;
        return $b;
    }

    private function equal($a, $b, $c) {
        if ($a == $b)
            return $a;
        if ($a == $c)
            return $a;
        if ($b == $c)
            return $b;
        return false;
    }

}