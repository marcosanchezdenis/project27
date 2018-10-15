<?php
class Adm_QueryObject_Docente_NextCodigo extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("max(d.codigo) as codigo")
             ->from("Model_Docente d");
    }
}