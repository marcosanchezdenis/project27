<?php
class Ins_QueryObject_Carrera_Habilitadas extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("c.carrera_id, (c.codigo || ' - ' || c.nombre) as nombre")
             ->from("Model_Carrera c")
             ->orderBy("c.nombre")
             ;
    }
}