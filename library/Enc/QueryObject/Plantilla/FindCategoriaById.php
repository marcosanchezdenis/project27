<?php
class Enc_QueryObject_Plantilla_FindCategoriaById extends Base_QueryObject
{
	public function __construct($id)
	{
		$this->doctrineQuery = Doctrine_Query::create()
			 ->from("Model_Categoria c")
			 ->where("c.categoria_id = ?", $id);
	}
}