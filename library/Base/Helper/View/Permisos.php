<?php
class Base_Helper_View_Permisos extends Zend_View_Helper_Abstract
{
	public $permisosPorUC = array(
        'adm' => array(
            'adm-usu', 'adm-doc',
            'ins-dep', 'ins-car', 'ins-asi',
            'enc-per', 'enc-esc', 'enc-pla', 'enc-enc',
            'inf-dep', 'inf-doc', 'inf-mat', 'inf-bru'
        ),
        'enc' => array(
            'adm-usu', 'adm-doc',
            'ins-asi',
            'enc-per', 'enc-esc', 'enc-pla', 'enc-enc',
            'inf-dep', 'inf-doc', 'inf-mat', 'inf-bru'
        ),
        'dir' => array(
            'adm-doc',
            'inf-dep', 'inf-doc', 'inf-mat', 'inf-bru'
        ),
        'sec' => array(
            'adm-doc',
            'ins-dep', 'ins-car', 'ins-asi',
            'enc-per', 'enc-enc',
            'inf-dep', 'inf-doc', 'inf-mat', 'inf-bru'
        ),
        'doc' => array(
            'inf-doc',
        ),
    );

	public $permisosPorModulo = array(
        'adm' => array('adm', 'ins', 'enc', 'inf'),
        'enc' => array('adm', 'ins', 'enc', 'inf'),
        'dir' => array('adm', 'inf'),
        'sec' => array('adm', 'ins', 'enc', 'inf'),
        'doc' => array('inf'),
    );
    
    public function permisos()
    {
        $user = $this->findLoggedUser();

		try{
			if(is_null($user)){
				$permisos = array();
			}else{
				$roles = $user->getRoles();
				$permisos = $this->getPermisos($roles);
			}
		}catch(\Exception $e){
			$permisos = array();
		}

		return $permisos;
    }
    
    /**
     * Obtiene el Usuario Logueado
     * @return Model_Usuario
     */
	public function findLoggedUser()
    {
		$auth = Zend_Auth::getInstance();
		$user = null;

		if($auth->hasIdentity()) {
			$user = $auth->getIdentity();
		}

        return $user;
    }
    
    /**
     * Obtiene los permisos de acuerdo a los roles del usuario
     * @param array $roles
     * @return array $permisos
     */
	public function getPermisos($roles)
    {
        $permisos = array();
        
        foreach ($roles as $rol) {
            $permisos = array_merge(
                $permisos,
                $this->permisosPorUC[$rol],
                $this->permisosPorModulo[$rol]
            );
        }
        
        return $permisos;
    }
}