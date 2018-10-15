<?php
class Ins_QueryObject_Departamento_Departamento extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("d.departamento_id, d.nombre")
             ->from("Model_Departamento d");
    }
}