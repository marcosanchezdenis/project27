<?php
class Enc_Repository_Periodo extends Base_Repository
{
    public function getQueryObject($id)
    {
        return new Enc_QueryObject_Periodo_Get($id);
    }
    
    public function find()
    {
        $query = new Enc_QueryObject_Periodo_Listado();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
    
    public function getPeriodos()
    {
        $query = new Enc_QueryObject_Periodo_Periodo();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
}