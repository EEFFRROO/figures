<?php

class Point {

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function setX($x) {
        $this->x = $x;
    }

    public function setY($y) {
        $this->y = $y;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function calcDistance($point) {
        $result = sqrt($this->squareOfDistance($point));
        return $result;
    }

    public function squareOfDistance($point) {
        $result = ($point->x - $this->x) * ($point->x - $this->x) + ($point->y - $this->y) * ($point->y - $this->y);
        return $result;
    }

}