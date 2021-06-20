<?php

class DB {

    public function __construct() {
        $this->db = new SQLite3("DB.db");
        $this->db->busyTimeout(200);
        $this->createTables();
    }

    public function __desctruct() {
        $this->db->close();
    }

    private function createTables() {
        // if (!$this->db->querySingle("SELECT count(*) FROM sqlite_master WHERE type='table' AND name='figures'")) { // если таблица не существует
        $query = "CREATE TABLE IF NOT EXISTS figures (id INTEGER PRIMARY KEY AUTOINCREMENT, type TEXT)";
        $this->db->exec($query);
        // }
        // if (!$this->db->querySingle("SELECT count(*) FROM sqlite_master WHERE type='table' AND name='points'")) { // если таблица не существует
        $query = "CREATE TABLE IF NOT EXISTS points (id INTEGER PRIMARY KEY AUTOINCREMENT, x INTEGER, y INTEGER)";
        $this->db->exec($query);
        // }
        // if (!$this->db->querySingle("SELECT count(*) FROM sqlite_master WHERE type='table' AND name='params'")) { // если таблица не существует
        $query = "CREATE TABLE IF NOT EXISTS params (id INTEGER PRIMARY KEY AUTOINCREMENT, figure_id INTEGER, type TEXT, 
                    point_id INTEGER, FOREIGN KEY (figure_id) REFERENCES figures (id), 
                    FOREIGN KEY (point_id) REFERENCES points (id))";
        $this->db->exec($query);
        // }
    }
    
    private function insertFigure($figure) {
        $query = "INSERT INTO figures (type) VALUES ('" . $figure . "')";
        $this->db->exec($query);
        return $this->db->lastInsertRowID();
    }
    
    private function insertPoint($x, $y) {
        if ($this->db->querySingle("SELECT count(*) FROM points WHERE x = " . $x . " AND y = " . $y)) {
            return $this->db->querySingle("SELECT id FROM points WHERE x = " . $x . " AND y = " . $y);
        }
        $query = "INSERT INTO points (x, y) VALUES (" . $x . ", " . $y . ")"; 
        $this->db->exec($query);
        return $this->db->lastInsertRowID();
    }

    private function insertParams($figureId, $type, $pointId) {
        $query = "INSERT INTO params (figure_id, type, point_id) 
                VALUES (" . $figureId . ", '" . $type . "', " . $pointId . ")"; 
        $this->db->exec($query);
        return $this->db->lastInsertRowID();
    }

    public function addCircle($xc, $yc, $xr, $yr) {
        $circleId = $this->insertFigure("circle");
        // Вставка точек
        $center = $this->insertPoint($xc, $yc);
        $radius = $this->insertPoint($xr, $yr);
        // Вставка параметров круга
        $this->insertParams($circleId, "center", $center);
        $this->insertParams($circleId, "radius", $radius);
    }

    public function addTriangle($x1, $y1, $x2, $y2, $x3, $y3) {
        $triangleId = $this->insertFigure("triangle");
        // Вставка точек
        $pointId1 = $this->insertPoint($x1, $y1);
        $pointId2 = $this->insertPoint($x2, $y2);
        $pointId3 = $this->insertPoint($x3, $y3);
        // Вставка параметров треугольника
        $this->insertParams($triangleId, "point1", $pointId1);
        $this->insertParams($triangleId, "point2", $pointId2);
        $this->insertParams($triangleId, "point3", $pointId3);
    }

    public function addParallelogram($x1, $y1, $x2, $y2, $x3, $y3) {
        $parallelogramId = $this->insertFigure("parallelogram");
        // Вставка точек
        $pointId1 = $this->insertPoint($x1, $y1);
        $pointId2 = $this->insertPoint($x2, $y2);
        $pointId3 = $this->insertPoint($x3, $y3);
        // Вставка параметров параллелограма
        $this->insertParams($parallelogramId, "point1", $pointId1);
        $this->insertParams($parallelogramId, "point2", $pointId2);
        $this->insertParams($parallelogramId, "point3", $pointId3);
    }

    public function getParams() {
        $query = "SELECT figures.id, figures.type as figureType, params.type as pointType, points.x, points.y 
                FROM params JOIN figures ON (params.figure_id = figures.id) 
                JOIN points ON (params.point_id = points.id)";
        return $this->db->query($query);
    }

}
