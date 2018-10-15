<?php
class Adm_Auth_Filter extends Zend_Controller_Plugin_Abstract
{
    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::routeShutdown()
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        // Si no esta autenticado, se le redirecciona al Login
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity() && $this->isInLoggedZone($request)) {
            $request->setModuleName('default');
            $request->setControllerName('login');
            $request->setActionName('index');
        }
    }
    
    protected function isInLoggedZone(Zend_Controller_Request_Abstract $request)
    {
    	// Si no esta en login ni en el modulo Rpta entonces esta en zona logueada
    	if ($request->getPathInfo() != '/login' && $request->getModuleName() != 'resp') {
    		return true;
    	}
    	return false;
    }
}