<?php
class Enc_Repository_Plantilla extends Base_Repository
{
	/**
	 * @return Model_Encuesta
	 */
	public function getQueryObject($id)
    {
    	return new Enc_QueryObject_Plantilla_Get($id);
    }
	
    /**
     * Listado de Plantillas existentes en el sistema
     * @param string $nombre
     * @return array
     */
    public function find($nombre = null)
    {
        $query = new Enc_QueryObject_Plantilla_Listado();
        if ($nombre != false && $nombre != "") {
            $query->addSearchByNombre($nombre);
        }
        
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    /**
     * Busca una encuesta a partir del ID de la misma
     * @param integer $id
     * @throws Exception Si no encuentra la plantilla (ID incorrecto)
     * @return multitype:number string date multitype: multitype:multitype:NULL
     */
    public function findById($id)
    {
    	$encuesta = $this->findModelById($id);
    	
    	if ($encuesta === null) {
    		throw new Exception("No se encontro la plantilla con id = $id");
    	}
    	
    	return $encuesta->getEstructura();
    }
    
    /**
     * @return Model_Categoria
     */
	public function findCategoriaById($id)
    {
    	$query = new Enc_QueryObject_Plantilla_FindCategoriaById($id);
    	$categoria = $query->executeAndGetFirst();
    	return $categoria;
    }
    
    /**
     * @return Model_Pregunta
     */
	public function findPreguntaById($id)
    {
    	$query = new Enc_QueryObject_Plantilla_FindPreguntaById($id);
    	$pregunta = $query->executeAndGetFirst();
    	return $pregunta;
    }
}