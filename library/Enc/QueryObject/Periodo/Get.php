<?php
class Enc_QueryObject_Periodo_Get extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->from("Model_PeriodoLectivo pl")
             ->leftJoin("pl.Periodo p")
             
             ->where('pl.periodo_lectivo_id = ?', $id);
    }
}