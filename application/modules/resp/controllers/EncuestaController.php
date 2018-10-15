<?php
class Resp_EncuestaController extends Base_Controller
{
    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            // Cambia el layout del sistema
            $this->_helper->layout->setLayout('resp-layout');
        }
    }
    
    public function getEncuestaData($encuesta)
    {
        return array(
            "enc_particular_id" => $encuesta->enc_particular_id,
            "identificador"     => $encuesta->identificador,
            "periodo"           => $encuesta->PeriodoLectivo->getPeriodoLectivo(),
            "docente"           => $encuesta->Docente->getNombre(),
            "asignatura"        => $encuesta->Asignatura->nombre,
            "password"          => is_null($encuesta->password) ? "" : $encuesta->password
        );
    }
    
	public function completeAction()
	{
		$this->view->mainTitle = "Completar Encuesta";
		
		$serial = $this->getParam("id");
		$encuesta = $this->getRepository()->findBySerial($serial);

		//VERIFICAR SI YA RESPONDIO LA ENCUESTA MEDIANTE COOKIES
		if(isset($_COOKIE['encs'])){
			$yaRespondio = false;

			foreach ($_COOKIE['encs'] as $name => $value) {
				$actualSerial = htmlspecialchars($value);
				if(strcmp($actualSerial, $serial) == 0){
					$yaRespondio = true;
				}
			}

			if($yaRespondio){
				$this->_redirect('/resp/encuesta/success');
				return;
			}
		}

		//VERIFICAR QUE NO SOBREPASE EL MAXIMO DE RESPUESTAS POSIBLES
		$cantRespActual = $this->getRepository()->getCantRespByEnc($encuesta->enc_particular_id);
		if($encuesta->max_resp > 0 && $cantRespActual >= $encuesta->max_resp) {
			$this->_redirect('/resp/encuesta/max-resp');
			return;
		}

		//VERIFICAR QUE LA ENCUESTA ESTE ACTIVA
		if($encuesta->activo != "S") {
			$this->_redirect('/resp/encuesta/no-activo/');
			return;
		}

		//VERIFICAR QUE SE ENCUENTRE EN EL RANGO DE FECHA APROPIADO
		$estaEnRangoFecha = $this->getRepository()->estaEnRangoFecha($encuesta->enc_particular_id, $encuesta->fecha_inicio, $encuesta->fecha_fin);
		if(!$estaEnRangoFecha){
			$this->_redirect('/resp/encuesta/fuera-rango');
			return;
		}

		$this->view->encuesta = $this->getEncuestaData($encuesta);
		$this->view->escala = $encuesta->Encuesta->Escala->getValores();
		$this->view->plantilla = $encuesta->Encuesta->getEstructura();

		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();

			$passSafe = false;

			if((!is_null($encuesta->password)) && $encuesta->password != ""){
				$password = $data["password"];

				if(strcmp($encuesta->password, $password) == 0){
					$passSafe = true;
				}
			}else{
				$passSafe = true;
			}

			if($passSafe){
				$builder = new Resp_Builder_Encuesta_Respuesta($data, $this->getRepository());
				$results = $builder->generateModels();

				if($results["status"]){
					$builder->save();
					setcookie('encs[' . $serial . ']', $serial, time() + 60*60*24*30, '/');
					$this->_redirect('/resp/encuesta/success');
				}

				$this->view->errors = $results["errors"];
			}else{
				$this->_redirect('/resp/encuesta/password-error');
			}
		}
	}

	public function noActivoAction()
	{
	    $this->view->mainTitle = "Encuesta No Activa";
	    $this->view->errors = $this->_helper->flashMessenger->getMessages();
	}

	public function passwordErrorAction()
	{
		$this->view->mainTitle = "Password Error";
		$this->view->errors = $this->_helper->flashMessenger->getMessages();
	}

	public function maxRespAction()
	{
		$this->view->mainTitle = "Encuesta Inabilitada";
		$this->view->errors = $this->_helper->flashMessenger->getMessages();
	}

	public function fueraRangoAction()
	{
		$this->view->mainTitle = "Encuesta Inabilitada";
		$this->view->errors = $this->_helper->flashMessenger->getMessages();
	}

	public function successAction()
	{
	    $this->view->mainTitle = "Respuestas";
	    
	    $this->view->mensaje = "GRACIAS";
	    $this->view->continuar = "SALIR";
	}
	
	public function getRepository()
	{
		return new Resp_Repository_Encuesta();
	}
}