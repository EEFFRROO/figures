<?php

spl_autoload_register(function ($class_name) {
    include __DIR__ . $class_name . '.php';
});

class Triangle extends Figure {

    public function __construct($x1, $y1, $x2, $y2, $x3, $y3) {
        $this->point1 = new Point($x1, $y1);
        $this->point2 = new Point($x2, $y2);
        $this->point3 = new Point($x3, $y3);
    }

    public function calcArea() {
        // s^2 = p(p-a)(p-b)(p-c)
        $a = $this->point1->calcDistance($this->point2);
        $b = $this->point2->calcDistance($this->point3);
        $c = $this->point3->calcDistance($this->point1);
        $p = ($a + $b + $c) / 2;
        $result = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
        return $result;
    }
}