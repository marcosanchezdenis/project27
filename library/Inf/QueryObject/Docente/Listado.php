<?php
class Inf_QueryObject_Docente_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("d.docente_id, d.codigo, d.activo")
             ->addSelect("p.persona_id, (p.apellido || ', ' || p.nombre) as docente")
             ->addSelect("p.email as email")
             
             ->from("Model_Docente d")
             ->leftJoin("d.Persona p")
			 ->orderBy("p.apellido")
			 ->addOrderBy("p.nombre");
    }
}