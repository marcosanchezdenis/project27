<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAppAutoloader()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'	=> APPLICATION_PATH
        ));
    }
    
    /**
     * Usado para utilizar el placeholder baseUrl en el Bootstrap
     */
    protected function _initRequest()
    {
        $this->bootstrap('FrontController');

		error_reporting(E_ERROR | E_PARSE);
		ini_set('memory_limit', '-1');
        
        $frontController = $this->getResource('FrontController');
        $request = new Zend_Controller_Request_Http();
        
        $frontController->setRequest($request);
    }
	
	/**
	 * Inicializacion de Doctrine
	 */
	protected function _initDoctrine()
	{
		$this->getApplication()->getAutoloader()
			 ->pushAutoloader(array('Doctrine', 'autoload'));
		$doctrineConfig = $this->getOption('doctrine');
		
		$manager = Doctrine_Manager::getInstance();
		$manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
		$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, $doctrineConfig['model_autoloading']);
		
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
		
		$conn = Doctrine_Manager::connection($doctrineConfig['dsn'], 'doctrine');
		$conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
		return $conn;
	}
	
	/**
	 * Inicializacion del contenido del Layout
	 */
	protected function _initLayoutContent()
	{
		$view = new Zend_View();
		
		$view->doctype('HTML5');
		
		// Titulo
		$view->headTitle('Encuestas Web')
		     ->setSeparator(' :: ');
        
		// CSS
		$view->headLink()->appendStylesheet($view->baseUrl('css/blueprint/screen.css'), 'screen, projection')
		                 ->appendStylesheet($view->baseUrl('css/blueprint/print.css'), 'print')
		                 ->appendStylesheet($view->baseUrl('css/blueprint/ie.css'), 'screen, projection', 'IE')
		                 
		                 ->appendStylesheet($view->baseUrl('css/smoothness/jquery-ui.css'))
//		                 ->appendStylesheet($view->baseUrl('css/smoothness/jquery-ui-min.css'))
		                 
		                 // LAYOUT DEL SITIO
		                 ->appendStylesheet($view->baseUrl('css/layout.css'), 'screen, projection')
		                 ->appendStylesheet($view->baseUrl('css/layout/header.css'), 'screen, projection')
		                 ->appendStylesheet($view->baseUrl('css/layout/menu.css'), 'screen, projection')
		                 ->appendStylesheet($view->baseUrl('css/layout/content.css'), 'screen, projection')
		                 ->appendStylesheet($view->baseUrl('css/layout/footer.css'), 'screen, projection')
		                 ;
		
		// JS
		$view->headScript()->appendFile($view->baseUrl('js/jquery.js'))
		                   ->appendFile($view->baseUrl('js/jquery-ui.js'))
		                   ->appendFile($view->baseUrl('js/layout/menu.js'))
		                   ;
		
		$helper_path = APPLICATION_PATH . '/../library/Base/Helper/View';
		$view->addHelperPath($helper_path, 'Base_Helper_View');
		
		Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setView($view);
		header('Content-Type: text/html; charset=utf-8');
		
		return $view;
	}
	
}