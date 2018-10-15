<?php
class Ins_QueryObject_Asignatura_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("a.asignatura_id, a.nombre, a.codigo, a.activo")
             ->addSelect("c.carrera_id, (c.nombre || ' (' || c.codigo || ')') as carrera")
             ->from("Model_Asignatura a")
             ->leftJoin("a.Carrera c")
             ->orderBy("a.codigo");
    }
}
