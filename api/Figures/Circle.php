<?php

spl_autoload_register(function ($class_name) {
    include __DIR__ . $class_name . '.php';
});

class Circle extends Figure {

    public function __construct($xc, $yc, $xr, $yr) {
        $this->center = new Point($xc, $yc);
        $this->radius = new Point($xr, $yr);
    }

    public function calcArea() {
        // s = pi * r^2
        $radius = $this->center->calcDistance($this->radius);
        $result = pi() * $radius * $radius;
        return $result;
    }
}