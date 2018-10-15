<?php
class Enc_QueryObject_Encuesta_CountResp extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			->addSelect("count(r.respuesta_id) as cnt_respuestas")
			->from("Model_EncParticular ep")
			->innerJoin("ep.Respuesta r")
			->where("ep.enc_particular_id = ?", $id);
	}
}