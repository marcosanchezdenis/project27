<?php
class Ins_Repository_Departamento extends Base_Repository
{
    /**
     * @return Model_Departamento
     */
    public function getQueryObject($id)
    {
        return new Ins_QueryObject_Departamento_Get($id);
    }
    
    public function find()
    {
        $query = new Ins_QueryObject_Departamento_Listado();
        $departamentos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $departamentos;
    }
    
    public function findById($id)
    {
        $query = new Ins_QueryObject_Departamento_Listado();
	    $query->addSearchById($id);
	    $departamento = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    return $departamento[0];
    }
    
	public function getAll()
    {
        $query = new Ins_QueryObject_Departamento_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    public function getDepartamentos()
    {
        $query = new Ins_QueryObject_Departamento_Departamento();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
}