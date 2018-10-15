<?php
class Base_Controller extends Zend_Controller_Action
{
    protected $homeURL;
    protected $contextSwitch;
    
    protected $permisosPorUC = array(
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
    
    /**
     * Inicializa las variables utilizadas del Controlador
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        // Control usuario autenticado
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/default/login/login');
        }
        
        // Setear Layout Principal
        $this->_helper->layout->setLayout('layout');
        
        // Definir home de usuario
        $this->setHomePage();
        
        // Inicializacion de Contextos
        $this->getContextSwitch()
             ->setAutoJsonSerialization(false)
             ->initContext();
    }
    
    /**
     * Redireccion a la pagina principal
     */
    protected function goHomePage()
    {
        $this->_redirect($this->homeURL);
    }
    
    /**
     * @return Zend_Controller_Action_Helper_ContextSwitch
     */
    protected function getContextSwitch()
    {
        if (!$this->contextSwitch) {
            $this->contextSwitch = $this->_helper->getHelper('contextSwitch');
        }
        
        return $this->contextSwitch;
    }
    
    protected function setHomePage()
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        $roles = $user->getRoles();
        
        if (in_array('adm', $roles)) {
            $this->homeURL = '/adm/usuario/index';
        } elseif (in_array('enc', $roles)) {
            $this->homeURL = '/enc/encuesta/index';
        } elseif (in_array('dir', $roles)) {
            $this->homeURL = '/inf/departamento/index';
        } elseif (in_array('sec', $roles)) {
            $this->homeURL = '/enc/encuesta/index';
        } elseif (in_array('doc', $roles)) {
            $this->homeURL = '/inf/docente/index';
        }
    }
    
    /**
     * Obtiene parametros enviados en el request
     * @param string $param
     * @param mixed $defaultRet
     */
    public function getParam($param, $defaultRet = false)
    {
        return $this->getRequest()->getParam($param, $defaultRet);
    }
    
    /**
     * Determina si el usuario tiene permisos sobre una operacion
     * @param string $operacion
     * @return boolean
     */
    protected function tienePermisos($operacion)
    {
        if (in_array($operacion, $this->getPermisos())) {
            return true;
        }
        return false;
    }
    
    /**
     * Listado de permisos del usuario logueado
     * @return array $permisos
     */
    private function getPermisos()
    {
        $user     = Zend_Auth::getInstance()->getIdentity();
        $roles    = $user->getRoles();
        $permisos = array();
        
        foreach ($roles as $rol) {
            $permisos = array_merge($permisos, $this->permisosPorUC[$rol]);
        }
        
        return $permisos;
    }
    
    public function throwGetParamException()
    {
        throw new Exception("No se pudieron recuperar los datos enviados");
    }
    
    public function getPayloadRequest()
    {
        $rawBody = $this->getRequest()->getRawBody();
        $decodedData = Zend_Json::decode($rawBody);
        return $decodedData;
    }
    
    public function getPostParams()
    {
        $data = $this->getPayloadRequest();
        if (isset($data[self::POST_KEY])) {
            return $data[self::POST_KEY];
        }
    	$this->throwGetParamException();
    }
    
    public function getPutParams()
    {
        $data = $this->getPayloadRequest();
        if (isset($data[self::PUT_KEY])) {
            return $data[self::PUT_KEY];
        }
        $this->throwGetParamException();
    }
    
    public function getDeleteParams()
    {
        $data = $this->getPayloadRequest();
        if (isset($data[self::DELETE_KEY])) {
            return $data[self::DELETE_KEY];
        }
        $this->throwGetParamException();
    }
}