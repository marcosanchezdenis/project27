<?php

/**
 * Funcionalidades de ingreso y salida del sistema
 * @author Guido Arce
 */
class LoginController extends Base_Controller
{
    
    /**
     * @see Base_Controller::init()
     */
    public function init()
    {
        // Cambio del layout del sistema
        $this->_helper->layout->setLayout('login-layout');
    }
    
    /**
     * Iniciar Sesion en el sistema
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $username = $this->getParam('usuario');
            $password = $this->getParam('contrasena');
            
            $adapter = new Adm_Auth_Adapter($username, $password);
            $result  = Zend_Auth::getInstance()->authenticate($adapter);
            
            if (Zend_Auth::getInstance()->hasIdentity()) {
                $this->setHomePage();
                $this->goHomePage();
            } else {
                $this->view->error = implode(' ', $result->getMessages());
            }
        }
    }
    
    /**
     * Cerrar Sesion en el sistema
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
    
}