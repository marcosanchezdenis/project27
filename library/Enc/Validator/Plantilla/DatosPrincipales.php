<?php
class Enc_Validator_Plantilla_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($plantilla)
	{
		$this->validateNombre($plantilla);
		$this->validateDate($plantilla);
		$this->validateEscala($plantilla);
	}
	
	public function isValid($plantilla)
	{
		$this->validateFields($plantilla);
		$isValidModel = true;
		
		$numOfValidators = count($this->validators);
		for ($i=0; $i<$numOfValidators; $i++) {
			$isValidField = $this->validators[$i]->isValid($this->validatedFields[$i]);
			if (!$isValidField) {
				$isValidModel = false;
				$this->validationErrors[] = $this->validators[$i]->getMessages();
			}
		}
		
		return $isValidModel;
	}
	
	public function validateNombre($plantilla)
	{
		$nombreValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'NOMBRE'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
		$lengthValidator->setMessage("Nombre muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$nombreValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $nombreValidator;
		$this->validatedFields[] = $plantilla->nombre;
	}
	
	public function validateDate($plantilla)
	{
		$fechaValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'FECHA'</b> es obligatorio");
		$dateValidator = new Zend_Validate_Date();
		$dateValidator->setMessage("Fecha inv&aacute;lida <em>(Formato requerido: d-m-A)</em>");
		
		$fechaValidator->addValidator($emptyValidator)
					   ->addValidator($dateValidator);
		
		$this->validators[] = $fechaValidator;
		$this->validatedFields[] = $plantilla->fecha;
	}
	
	public function validateEscala($plantilla)
	{
		$escalaRepository = new Enc_Repository_Escala();
		$escalas = $escalaRepository->getAllIds();
		
		$escalaValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'ESCALA'</b> es obligatorio");
		$existValidator = new Zend_Validate_InArray($escalas);
		$existValidator->setMessage("Escala inv&aacute;lida <em>(Seleccione una escala correcta)</em>");
		
		$escalaValidator->addValidator($emptyValidator)
			 			->addValidator($existValidator);
		
		$this->validators[] = $escalaValidator;
		$this->validatedFields[] = $plantilla->escala_id;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}