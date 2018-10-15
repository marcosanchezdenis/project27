<?php
class Enc_QueryObject_Periodo_Habilitados extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("pl.periodo_lectivo_id")
             ->addSelect("(p.periodo || ' ' || pl.anho_lectivo) as periodo")
             
             ->from("Model_PeriodoLectivo pl")
             ->leftJoin("pl.Periodo p")
             
             ->where("pl.activo = 'S'")
             
             ->orderBy("pl.anho_lectivo")
             ->addOrderBy("p.periodo")
             ;
    }
}