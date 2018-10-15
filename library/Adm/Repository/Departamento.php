<?php
class Adm_Repository_Departamento extends Base_Repository
{
    /**
     * @return Model_Departamento
     */
    public function getQueryObject($id)
    {
        return new Adm_QueryObject_Departamento_Get($id);
    }
    
    public function find()
    {
        $query = new Adm_QueryObject_Departamento_Listado();
        $departamentos = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $departamentos;
    }
    
    public function findById($id)
    {
        $query = new Adm_QueryObject_Departamento_Listado();
	    $query->addSearchById($id);
	    $departamento = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    return $departamento[0];
    }
}