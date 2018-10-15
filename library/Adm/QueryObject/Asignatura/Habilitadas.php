<?php
class Adm_QueryObject_Asignatura_Habilitadas extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("a.asignatura_id, a.nombre")
             ->from("Model_Asignatura a")
             ->where("a.activo = 'S'")
             ->orderBy("a.nombre")
             ;
    }
}
