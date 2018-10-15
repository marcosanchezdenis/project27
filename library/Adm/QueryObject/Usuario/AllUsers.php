<?php
class Adm_QueryObject_Usuario_AllUsers extends Base_QueryObject
{
	public function __construct()
	{
		$this->doctrineQuery = Doctrine_Query::create()->from("Model_Usuario u");
	}
}