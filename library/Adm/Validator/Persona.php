<?php
class Adm_Validator_Persona extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($persona)
	{
		$this->validateNombre($persona);
		$this->validateApellido($persona);
	}
	
	public function isValid($persona)
	{
		$this->validateFields($persona);
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
	
	public function validateNombre($persona)
	{
		$nombreValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'NOMBRE'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
		$lengthValidator->setMessage("Nombre muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$nombreValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $nombreValidator;
		$this->validatedFields[] = $persona->nombre;
	}
	
	public function validateApellido($persona)
	{
		$apellidoValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'APELLIDO'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
		$lengthValidator->setMessage("Apellido muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$apellidoValidator->addValidator($emptyValidator)
			 			  ->addValidator($lengthValidator);
		
		$this->validators[] = $apellidoValidator;
		$this->validatedFields[] = $persona->apellido;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}