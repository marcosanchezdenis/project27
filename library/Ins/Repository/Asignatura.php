<?php
class Ins_Repository_Asignatura extends Base_Repository
{
    /**
     * @return Model_Asignatura
     */
    public function getQueryObject($id)
    {
        return new Ins_QueryObject_Asignatura_Get($id);
    }
	
    public function find()
    {
        $query = new Ins_QueryObject_Asignatura_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    public function findById($id)
    {
        $asignatura = $this->findModelById($id);
        return $asignatura->toArray();
    }
    
    public function getCarreras()
    {
        $query = new Ins_QueryObject_Carrera_Habilitadas();
        $carreras = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $carreras;
    }
}