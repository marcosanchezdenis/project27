<?php
class Ins_QueryObject_Facultad_Facultad extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("f.facultad_id, f.nombre")
             ->from("Model_Facultad f");
    }
}