<?php
class Ins_QueryObject_Carrera_Listado extends Base_QueryObject
{
    public function __construct()
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->addSelect("c.carrera_id, c.nombre, c.codigo")
             ->addSelect("d.departamento_id, d.nombre as departamento")
             ->from("Model_Carrera c")
             ->leftJoin("c.Departamento d")
             ->orderBy("c.codigo");
    }
    
    public function addSearchById($id)
    {
        $this->doctrineQuery->addWhere("c.carrera_id = ?", $id);
    }
}