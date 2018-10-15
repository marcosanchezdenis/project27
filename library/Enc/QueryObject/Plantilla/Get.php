<?php
class Enc_QueryObject_Plantilla_Get extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Encuesta e")
			 ->where("e.encuesta_id = ?", $id);
	}
}