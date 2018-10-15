<?php
class Inf_QueryObject_Docente_Informes extends Base_QueryObject
{
    public function __construct($docente_id, $periodo_lectivo_id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("a.nombre as asignatura")
             
             ->from("Model_EncParticular ep")
             ->leftJoin("ep.Asignatura a")
             
             ->where("ep.docente_id = ?", $docente_id)
             ->addWhere("ep.periodo_lectivo_id = ?", $periodo_lectivo_id);
    }
}