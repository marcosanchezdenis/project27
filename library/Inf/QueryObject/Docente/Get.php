<?php
class Inf_QueryObject_Docente_Get extends Base_QueryObject
{
    public function __construct($id)
    {
        $this->doctrineQuery = Doctrine_Query::create()
             ->from("Model_Docente d")
             ->where("d.docente_id = ?", $id);
    }
}