<?php
class Enc_QueryObject_Plantilla_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("e.encuesta_id, e.nombre, e.descripcion")
             ->addSelect("e.activo, to_char(e.fecha,'dd/mm/yyyy') as fecha")
             
             ->from("Model_Encuesta e")
             
             ->orderBy("e.fecha DESC")
             ->addOrderBy("e.nombre");
    }
    
    public function addSearchByNombre($nombre)
    {
        $this->doctrineQuery->addWhere("e.nombre ILIKE '%$nombre%'");
    }
}