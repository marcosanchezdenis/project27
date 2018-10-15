<?php
class Enc_QueryObject_Plantilla_FindPreguntaById extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Pregunta p")
			 ->leftJoin("p.PreguntaSm ps")
			 ->where("p.pregunta_id = ?", $id);
	}
}