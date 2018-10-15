<?php
class Enc_Repository_Escala extends Base_Repository
{
    /**
     * @return Model_Escala
     */
    public function getQueryObject($id)
    {
    	return new Enc_QueryObject_Escala_Get($id);
    }
	
    public function find($searchParams = null)
    {
        $query = new Enc_QueryObject_Escala_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    public function getAll()
    {
        $query = new Enc_QueryObject_Escala_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    public function findById($id)
    {
        $escala = $this->findModelById($id);
        
        if ($escala === null) {
            throw new Exception("No se encontro la escala con id = $id");
        }
        
        $escalaData = $escala->toArray(true);
        
        if (count($escala->Encuesta) > 0) {
            $escalaData['usado'] = 'S';
        } else {
            $escalaData['usado'] = 'N';
        }
        
        return $escalaData;
    }
    
    public function getAllIds()
    {
    	$ids = array();
    	$escalas = $this->getAll();
    	foreach ($escalas as $escala) {
    		$ids[] = $escala["escala_id"];
    	}
    	return $ids;
    }
}