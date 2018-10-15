<?php
class Adm_Repository_Rol
{
	public function getAll()
    {
        $query = new Adm_QueryObject_Rol_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
}