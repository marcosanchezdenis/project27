<?php
class Base_QueryObject
{
    protected $doctrineQuery;
    
    public function getDoctrineQuery()
    {
        return $this->doctrineQuery;
    }
    
    public function executeAndGetFirst()
    {
        $rs = $this->getDoctrineQuery()->execute(array());
        return $rs->getFirst();
    }
}