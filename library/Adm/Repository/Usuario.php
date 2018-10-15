<?php

/**
 * Repositorio de Usuarios
 * @author Guido Arce
 */
class Adm_Repository_Usuario extends Base_Repository
{
    /**
     * Listado de Usuarios con Datos Personales
     * @return Adm_QueryObject_Usuario_Listado
     */
    protected function getFindQueryObject()
    {
        return new Adm_QueryObject_Usuario_Listado();
    }
    
    /**
     * Modelo del Usuario seleccionado
     * @return Adm_QueryObject_Usuario_Model
     */
    protected function getQueryObject($id)
    {
        return new Adm_QueryObject_Usuario_Model($id);
    }
    
    /**
     * Datos del Usuario seleccionado
     * @param int $id
     * @return array
     */
    public function findById($id)
    {
        $user  = $this->findModelById($id);
        $roles = $user->getRolesAsignados();
    
        $usuarioData = $user->toArray();
        $personaData = $user->Persona->toArray();
        return array_merge($usuarioData, $personaData, $roles);
    }
    
	public function getLoggedUser()
	{
		return Zend_Auth::getInstance()->getIdentity();
	}

	public function getUsersList()
	{
		$query = new Adm_QueryObject_Usuario_AllUsers();
		$users = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		return $users;
	}
}