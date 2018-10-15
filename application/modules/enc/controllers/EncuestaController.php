<?php
class Enc_EncuestaController extends Base_Controller
{
    const CODIGO_OPERACION = 'enc-enc';
    
    public function init()
    {
        parent::init();
    
        if (!$this->tienePermisos(self::CODIGO_OPERACION)) {
            $this->goHomePage();
        }
    }
    
    public function indexAction()
    {
        $this->view->mainTitle = "Listado de Encuestas";

		$this->view->periodos    = $this->getPeriodos();
		$this->view->docentes    = $this->getDocentes();
		$this->view->asignaturas = $this->getAsignaturas();

		$this->view->perLecSel = "none";
		$this->view->asigSel = "none";
		$this->view->docenteSel = "none";

		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();

			if(isset($data['periodo_lectivo_id'])) {
				$this->view->perLecSel = $data['periodo_lectivo_id'];
			}

			if(isset($data['asignatura_id'])) {
				$this->view->asigSel = $data['asignatura_id'];
			}

			if(isset($data['docente_id'])) {
				$this->view->docenteSel = $data['docente_id'];
			}
		}

        $this->view->errors    = $this->_helper->flashMessenger->getMessages();
        
        $this->view->encuestas = $this->getRepository()->findFiltered(
			$this->view->perLecSel,
			$this->view->asigSel,
			$this->view->docenteSel
		);
    }
    
    protected function getPlantillas()
    {
        $plantillas = $this->getRepository()->getPlantillas();
        return $plantillas;
    }
    
    protected function getPeriodos()
    {
        $periodos = $this->getRepository()->getPeriodos();
        return $periodos;
    }
    
    protected function getDocentes()
    {
        $docentes = $this->getRepository()->getDocentes();
        return $docentes;
    }
    
    protected function getAsignaturas()
    {
        $asignaturas = $this->getRepository()->getAsignaturas();
        return $asignaturas;
    }
    
    public function crearAction()
    {
        $this->view->mainTitle = "Creaci&oacute;n de Encuestas";
        
        $this->view->plantillas  = $this->getPlantillas();
        $this->view->periodos    = $this->getPeriodos();
        $this->view->docentes    = $this->getDocentes();
        $this->view->asignaturas = $this->getAsignaturas();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

			if($data["password"] == "")
				$data["password"] = null;

			if($data["fecha_inicio"] == "")
				$data["fecha_inicio"] = null;

			if($data["fecha_fin"] == "")
				$data["fecha_fin"] = null;

			if($data["max_resp"] == "")
				$data["max_resp"] = 0;

            $builder = new Enc_Builder_Encuesta_DatosPrincipales($data, $this->getRepository());
            $results = $builder->generateModels();
            	
            if ($results["status"]) {
                $id = $builder->save();
                $this->_redirect('/enc/encuesta/get?id=' . $id);
            }
            	
            $this->view->encuesta = $data;
            $this->view->errors = $results["errors"];
        }
    }
    
    public function editarAction()
    {
        $this->view->mainTitle = "Edici&oacute;n de Encuestas";
        $this->view->plantillas  = $this->getPlantillas();
        $this->view->periodos    = $this->getPeriodos();
        $this->view->docentes    = $this->getDocentes();
        $this->view->asignaturas = $this->getAsignaturas();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

			if($data["password"] == "")
				$data["password"] = null;

			if($data["fecha_inicio"] == "")
				$data["fecha_inicio"] = null;

			if($data["fecha_fin"] == "")
				$data["fecha_fin"] = null;

			if($data["max_resp"] == "")
				$data["max_resp"] = 0;

            $id = $data["encuesta_id"];
            	
            $builder = new Enc_Builder_Encuesta_DatosPrincipales($data, $this->getRepository());
            $results = $builder->generateModels();
            	
            if ($results["status"]) {
                $builder->save();
                $this->_redirect('/enc/encuesta/index?id=' . $id);
            }
            	
            $this->view->results = $data;
            $this->view->errors = $results["errors"];
            $this->_redirect('/enc/encuesta/editar?id=' . $id);
        } else {
            $id = $this->getParam("id");
            	
            $encuesta = $this->getRepository()->findModelById($id);
            if (count($encuesta->Respuesta)) {
                $this->_helper->flashMessenger->addMessage("La encuesta no se puede editar <em><b>(La encuesta ya tiene respuestas)</b></em>");
                $this->_redirect('/enc/encuesta/index');
            }
            	
            $results = $this->getRepository()->findById($id);
            $this->view->encuesta    = $results;
			$this->view->enc = $encuesta;
        }
    }
    
    public function borrarAction()
    {
        $id = $this->getParam("id");
        $encuesta = $this->getRepository()->findModelById($id);
        
        if (count($encuesta->Respuesta)) {
            $this->_helper->flashMessenger->addMessage("La encuesta no se puede borrar <em><b>(La encuesta ya tiene respuestas)</b></em>");
            $this->_redirect('/enc/encuesta/index');
        } else {
            $encuesta->delete();
            $this->_redirect('/enc/encuesta/index');
        }
    }
    
    public function activarAction()
    {
        $id = $this->getParam("id");
        $encuesta = $this->getRepository()->findModelById($id);
        
        $encuesta->activo = ($encuesta->activo == "S") ? ("N") : ("S");
        $encuesta->save();
        
        $this->_redirect('/enc/encuesta/index');
    }
    
    protected function esEquipoEncuestadores()
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        if ($user->hasRol('enc')) {
            return true;
        }
        
        return false;
    }

	public function getCantRespuestas($id_encuesta){
		$query = new Enc_QueryObject_Encuesta_CountResp($id_encuesta);
	    $data  = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	    return $data[0]['cnt_respuestas'];
	}
    
    public function getAction()
    {
        $this->view->mainTitle = "Encuesta Realizada";
        $id = $this->getParam("id");
        $encuesta = $this->getRepository()->findById($id);
        $this->view->encuesta = $encuesta;
        $this->view->totalEncuestas = $this->getCantRespuestas($id);
        $this->view->shortUrl = Base_BitlyAPI::make_bitly_url(
			$this->view->serverUrl().$this->view->baseUrl("resp/encuesta/complete?id=" . $encuesta["identificador"])
		);
    }
    
    public function getRepository()
    {
        return new Enc_Repository_Encuesta();
    }
}