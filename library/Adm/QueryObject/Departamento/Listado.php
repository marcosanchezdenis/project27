<?php
class Adm_QueryObject_Departamento_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("d.nombre, d.codigo")
             ->addSelect("f.facultad_id, f.nombre as facultad")
             ->from("Model_Departamento d")
             ->leftJoin("d.Facultad f");
    }
    
    public function addSearchById($id)
    {
        $this->doctrineQuery->addWhere("d.departamento_id = ?", $id);
    }
}