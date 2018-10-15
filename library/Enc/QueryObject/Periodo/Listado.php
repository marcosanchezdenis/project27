<?php
class Enc_QueryObject_Periodo_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("pl.periodo_lectivo_id, pl.anho_lectivo as anho, pl.activo, pl.visible_profesores")
             ->addSelect("p.periodo_id, p.periodo as periodo")
             
             ->from("Model_PeriodoLectivo pl")
             ->leftJoin("pl.Periodo p")
             
             ->orderBy("pl.anho_lectivo, p.periodo");
    }
}