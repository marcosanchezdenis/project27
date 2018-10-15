<?php
class Adm_QueryObject_Asignatura_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("a.asignatura_id, a.nombre, a.codigo, a.activo")
             ->from("Model_Asignatura a")
             ->orderBy("a.codigo");
    }
}