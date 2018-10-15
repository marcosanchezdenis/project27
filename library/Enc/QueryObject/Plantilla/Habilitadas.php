<?php
class Enc_QueryObject_Plantilla_Habilitadas extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("e.encuesta_id, e.nombre")
             ->from("Model_Encuesta e")
             ->where("e.activo = 'S'")
             ->orderBy("e.fecha")
             ;
    }
}