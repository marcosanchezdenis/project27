<?php

/**
 * Repositorio
 * @author guido
 */
class Base_Repository
{
    /**
     * Implementado en cada repositorio especifico
     * @return Base_QueryObject
     */
    protected function getFindQueryObject()
    {
        // Implementar en la clase mas especifica
    }
    
    /**
     * Listado de registros dependiendo del Query realizado
     * @todo Agregar busqueda, paginacion, ordenamiento
     */
    public function find()
    {
        $query = $this->getFindQueryObject();
        
        $results = $query->getDoctrineQuery()
                         ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        
        return $results;
    }
    
	/**
	 * Implementado en cada repositorio especifico
	 * @param integer $id
	 * @return Base_QueryObject
	 */
	protected function getQueryObject($id)
	{
		// Implementar en cada repository
	}
	
	/**
	 * @return Doctrine_Record
	 */
	public function findModelById($id)
	{
		$query = $this->getQueryObject($id);
		$model = $query->executeAndGetFirst();
		return $model;
	}
}