<?php
class Enc_QueryObject_Periodo_Periodo extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("p.periodo_id, p.periodo")
             ->from("Model_Periodo p");
    }
}