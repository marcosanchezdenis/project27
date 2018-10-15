<?php
class Base_Helper_View_Breadcrumb extends Zend_View_Helper_Abstract
{
	protected $_request;
	protected $_separator = '  &raquo;  ';
	protected $_breadcrumb = array();
	
	protected $modulesTable = array(
		'enc'  => 'Encuestas',
		'adm'  => 'Administracion',
	    'ins'  => 'Institucion',
		'inf'  => 'Informes',
		'resp' => 'Respuestas'
	);
	
	protected $controllersTable = array(
		'plantilla'    => 'Plantillas',
		'escala' 	   => 'Escalas',
		'encuesta'     => 'Encuestas',
		'usuario' 	   => 'Usuarios',
		'departamento' => 'Departamentos',
		'docente'      => 'Docentes',
		'asignatura'   => 'Asignaturas',
	    'carrera'      => 'Carreras',
	    'periodo'      => 'Periodos',
	);
	
	public function __construct()
	{
		$fc = Zend_Controller_Front::getInstance();
		$this->_request = $fc->getRequest();
	}
	
	public function setSeparator($separator)
	{
		$this->_separator = $separator;
	}
	
	public function set(array $breadcrumb)
	{
		$this->_breadcrumb = $breadcrumb;
		return $this;
	}
	
	public function convert($data, $type)
	{
		switch ($type) {
			case 'M':
				$data = $this->modulesTable[$data];
				break;
			case 'C':
				$data = $this->controllersTable[$data];
				break;
			case 'A':
				//$data = $this->actionsTable[$data];
				$data = "";
				break;
		}
		
		return $data;
	}
	
	public function breadcrumb(array $breadcrumb = array())
	{
		if (empty($this->_breadcrumb)) {
			if (!empty($breadcrumb)) {
				$this->set($breadcrumb);
			} else {
				$module     = $this->_request->getModuleName();
				$controller = $this->_request->getControllerName();
				$action     = $this->_request->getActionName();
				
				if ($module != 'default') {
					$this->_breadcrumb[] = array(
						'title' => $this->convert($module, 'M'),
						'url' => '#'//$this->view->url(array('module' => $module), 'default', true)
					);
				}
				
				//if ($controller != 'index') {
					$this->_breadcrumb[] = array(
						'title' => $this->convert($controller, 'C'),
						'url' => '#'//$this->view->url(array('module' => $module, 'controller' => $controller), 'default', true)
					);
				//}
				
				//if ($action != 'index') {
					$this->_breadcrumb[] = array(
						'title' => $this->convert($action, 'A'),
						'url' => '#'//$this->view->url(array('module' => $module, 'controller' => $controller, 'action' => $action), 'default', true)
					);
				//}
				
				$this->_breadcrumb[count($this->_breadcrumb) - 1]['url'] = null;
			}
		}
		
		return $this;
	}
	
	public function __toString()
	{
		if (count($this->_breadcrumb) == 1) {
			$breadcrumb = '';
		} else {
			//$breadcrumb = '<ol class="breadcrumb">';
			$breadcrumb = '';
			
			foreach ($this->_breadcrumb as $i => $bc) {
				//$breadcrumb .= '<li>' . ($i != 0 ? '<span>' . $this->_separator . '</span>' : null);
				$breadcrumb .= ($i != 0 ? $this->_separator : null);
				
				if ($bc['url'] === null) {
					$breadcrumb .= $this->view->escape($bc['title']);
				} else {
					$breadcrumb .= '<a href="' . $bc['url'] . '">' . $this->view->escape($bc['title']) . '</a>';
				}
				
				//$breadcrumb .= '</li>';
			}
			
			//$breadcrumb .= '</ol>';
		}
		
		return $breadcrumb;
	}
}