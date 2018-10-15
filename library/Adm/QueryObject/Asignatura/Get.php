<?php
class Adm_QueryObject_Asignatura_Get extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->from("Model_Asignatura a")
             ->where("a.asignatura_id = ?", $id);
    }
}