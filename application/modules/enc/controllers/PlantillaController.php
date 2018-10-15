<?php
class Enc_PlantillaController extends Base_Controller
{
    const CODIGO_OPERACION = 'enc-pla';
    
	public function init()
	{
	    $this->getContextSwitch()
	         ->addActionContext('get-categoria', 'json')
	         ->addActionContext('get-pregunta', 'json');
	    
		parent::init();
		
		if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
		    $this->goHomePage();
		}
	}
	
    /**
	 * Listado de Plantillas del sistema
	 */
	public function indexAction()
	{
		$this->view->mainTitle = "Listado de Plantillas";
		$this->view->errors    = $this->_helper->flashMessenger->getMessages();
		
		$nombre     = $this->getParam("nombre");
		$plantillas = $this->getRepository()->find($nombre);
		
		$this->view->plantillas = $plantillas;
	}
	
	/**
	 * Visualiza una encuesta
	 */
	public function getAction()
	{
		$this->view->mainTitle = "Ver Plantilla";
		
		$id = $this->getParam("id");
		$plantilla = $this->getRepository()->findById($id);
		
		$this->view->plantilla = $plantilla;
	}
	
	public function activarAction()
	{
		$id = $this->getParam("id");
	    $plantilla = $this->getRepository()->findModelById($id);
	    
	    $plantilla->activo = ($plantilla->activo == "S") ? ("N") : ("S");
	    $plantilla->save();
	    
	    $this->_redirect('/enc/plantilla/index');
	}
	
	/**
	 * Recupera todas las escalas creadas en el sistema
	 * @return array $escalas array con todos los datos de las escalas
	 */
	protected function getEscalas()
	{
	    $escalaRepository = new Enc_Repository_Escala();
	    $escalas = $escalaRepository->getAll();
	    return $escalas;
	}
	
	/**
	 * Crea los datos principales de una nueva plantilla
	 */
	public function crearAction()
	{
	    $this->view->mainTitle = "Nueva Plantilla";
		$this->view->escalas = $this->getEscalas();
		
		if ($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Plantilla_DatosPrincipales($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $id);
			}
			
			$this->view->encuesta = $data;
			$this->view->errors = $results["errors"];
		}
	}
	
	/**
	 * Edita los datos principales de una plantilla
	 */
	public function editarAction()
	{
		$this->view->mainTitle = "Edici&oacute;n de Plantilla";
		$this->view->escalas = $this->getEscalas();
		
		if ($this->getRequest()->isPost()) {
		    $data = $this->getRequest()->getPost();
			$id = $data["encuesta_id"];
			
			$builder = new Enc_Builder_Plantilla_DatosPrincipales($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $id);
			}
			
			$this->view->results = $data;
			$this->view->errors = $results["errors"];
			$this->_redirect('/enc/plantilla/editar?id=' . $id);
		} else {
			$id = $this->getParam("id");
			
			$plantilla = $this->getRepository()->findModelById($id);
			if (count($plantilla->EncParticular)) {
			    $this->_helper->flashMessenger->addMessage("La plantilla no se puede editar <em><b>(Ya es utilizada por una encuesta)</b></em>");
			    $this->_redirect('/enc/plantilla/index');
			}
			
			$results = $this->getRepository()->findById($id);
			$this->view->results = $results;
		}
	}
	
	public function borrarAction()
	{
		$id = $this->getParam("id");
		$plantilla = $this->getRepository()->findModelById($id);
		
		if (count($plantilla->EncParticular)) {
			$this->_helper->flashMessenger->addMessage("La plantilla no se puede borrar <em><b>(Ya es utilizada por una encuesta)</b></em>");
			$this->_redirect('/enc/plantilla/index');
		} else {
			$plantilla->borrar();
			$this->_redirect('/enc/plantilla/index');
		}
	}
	
	public function estructuraAction()
	{
		$this->view->mainTitle = "Estructura";
		
		$id = $this->getParam("id");
		$plantilla = $this->getRepository()->findModelById($id);
		if (count($plantilla->EncParticular)) {
		    $this->_helper->flashMessenger->addMessage("La plantilla no se puede editar <em><b>(Ya es utilizada por una encuesta)</b></em>");
		    $this->_redirect('/enc/plantilla/index');
		}
		
		$results = $this->getRepository()->findById($id);
		$this->view->results = $results;
	}
	
	public function getCategoriaAction()
	{
		$id = $this->getParam("id");
		$categoria = $this->getRepository()->findCategoriaById($id);
		$this->_helper->json($categoria->toArray());
	}
	
	public function categoriaAction()
	{
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Plantilla_Categoria($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $data["redirect"]);
			}
		}
	}
	
	public function borrarCategoriaAction()
	{
		$id = $this->getParam("id");
		$enc = $this->getParam("enc");
		
		$categoria = $this->getRepository()->findCategoriaById($id);
		$categoria->borrar();
		
		$this->_redirect('/enc/plantilla/estructura?id=' . $enc);
	}
	
	public function getPreguntaAction()
	{
		$id = $this->getParam("id");
		$pregunta = $this->getRepository()->findPreguntaById($id);
		$this->_helper->json($pregunta->toArray(true));
	}
	
	public function pregAbiertaAction()
	{
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Plantilla_PregAbierta($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $data["redirect"]);
			}
		}
	}
	
	public function pregEscalaAction()
	{
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Plantilla_PregEscala($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $data["redirect"]);
			}
		}
	}
	
	public function pregSmAction()
	{
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			
			$builder = new Enc_Builder_Plantilla_PregSm($data, $this->getRepository());
			$results = $builder->generateModels();
			
			if ($results["status"]) {
				$id = $builder->save();
				$this->_redirect('/enc/plantilla/estructura?id=' . $data["redirect"]);
			}
		}
	}
	
	public function borrarPreguntaAction()
	{
		$id = $this->getParam("id");
		$enc = $this->getParam("enc");
		
		$pregunta = $this->getRepository()->findPreguntaById($id);
		$pregunta->borrar();
		
		$this->_redirect('/enc/plantilla/estructura?id=' . $enc);
	}
	
	public function getRepository()
	{
		return new Enc_Repository_Plantilla();
	}
}