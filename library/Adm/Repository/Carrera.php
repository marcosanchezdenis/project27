<?php
class Adm_Repository_Carrera extends Base_Repository
{
    /**
     * @return Model_Carrera
     */
    public function getQueryObject($id)
    {
        return new Adm_QueryObject_Carrera_Get($id);
    }
	
    public function find()
    {
        $query = new Adm_QueryObject_Carrera_Listado();
        $carreras = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $carreras;
    }
    
    public function findById($id)
	{
	    $query = new Adm_QueryObject_Carrera_Listado();
	    $query->addSearchById($id);
	    $carrera = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    return $carrera[0];
    }
}