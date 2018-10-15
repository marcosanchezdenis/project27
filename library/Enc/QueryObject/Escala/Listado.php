<?php
class Enc_QueryObject_Escala_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("e.escala_id, e.nombre, e.descripcion")
             ->addSelect("count(en.escala_id) as usado")
             
             ->from("Model_Escala e")
             ->leftJoin("e.Encuesta en")
             
             ->groupBy("e.escala_id, e.nombre, e.descripcion")
             ->orderBy("e.nombre DESC");
    }
}