<?php
class Ins_Repository_Facultad extends Base_Repository
{
    public function getFacultades()
    {
        $query = new Ins_QueryObject_Facultad_Facultad();
        $results = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $results;
    }
}