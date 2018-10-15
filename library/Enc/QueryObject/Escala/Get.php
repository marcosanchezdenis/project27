<?php
class Enc_QueryObject_Escala_Get extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->from("Model_Escala e")
             ->leftJoin("e.EscalaValor ev")
             ->where("e.escala_id = ?", $id);
    }
}