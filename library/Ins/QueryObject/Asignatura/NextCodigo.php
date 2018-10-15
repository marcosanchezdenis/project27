<?php
class Ins_QueryObject_Asignatura_NextCodigo extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("max(a.codigo) as codigo")
             ->from("Model_Asignatura a");
    }
}