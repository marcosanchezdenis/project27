<?php

/**
 * Model_Usuario
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Usuario extends Model_Base_Usuario
{
    // Respuestas de autenticacion
    const USER_NOT_FOUND  = 1;
    const WRONG_PASSWORD  = 2;
    const USER_NOT_ACTIVE = 3;
    
    /**
     * Obtiene el usuario a partir del nombre de usuario
     * @param string $username
     * @return Model_Usuario|false $user
     */
    protected function getUserByUsername($username)
    {
        $user = Doctrine_Core::getTable('Model_Usuario')->findOneBy('usuario', $username);
        if ($user instanceof Model_Usuario) {
            return $user;
        }
        return false;
    }
    
    /**
     * Autenticador de Usuarios
     * @param string $username
     * @param string $password
     * @throws Exception
     * @return Model_Usuario $user
     */
    public static function authenticate($username, $password)
    {
        $user = self::getUserByUsername($username);
        
        // Usuario no encontrado
        if (!$user) {
            throw new Exception(self::USER_NOT_FOUND);
        }
        
        // Contrasena incorrecta
        if ($user->contrasena != sha1($password)) {
            throw new Exception(self::WRONG_PASSWORD);
        }
        
        // Usuario inactivo
        if ($user->activo != 'S') {
            throw new Exception(self::USER_NOT_ACTIVE);
        }
        
        return $user;
    }
    
    /**
     * Obtiene los roles del Usuario
     * @return multitype:NULL |multitype:multitype:
     */
    public function getRoles()
    {
        $roles = array();
        
        foreach ($this->UsuarioPerm as $permiso) {
            $roles[] = $permiso->Rol->codigo;
        }
        
        $es_docente = $this->Persona->es_docente;
        if ($es_docente == 'S') {
            $roles[] = 'doc';
        }
        
        return $roles;
    }
    
    public function hasRol($rol)
    {
        if (in_array($rol, $this->getRoles())) {
            return true;
        }
        return false;
    }
    
    public function getRolesAsignados()
    {
        $roles = array_map(function ($permiso) {
            return array(
                "rol_id" => $permiso->Rol->rol_id,
                "rol"    => $permiso->Rol->nombre,
            );
        }, $this->UsuarioPerm->getData());
        
        return array("roles" => $roles);
    }
    
    public function getUsername()
    {
    	return $this->usuario;
    }
}