<?php
class Adm_QueryObject_Docente_Habilitados extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("d.docente_id")
             ->addSelect("(p.apellido || ', ' || p.nombre) as nombre")
             
             ->from("Model_Docente d")
             ->leftJoin("d.Persona p")
             
             ->where("d.activo = 'S'")
             ->orderBy("p.apellido")
             ;
    }
}