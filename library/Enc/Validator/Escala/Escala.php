<?php
class Enc_Validator_Escala_Escala extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($escala)
	{
		$this->validateNombre($escala);
	}
	
	public function isValid($escala)
	{
		$this->validateFields($escala);
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
	
	public function validateNombre($escala)
	{
		$nombreValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'NOMBRE'</b> es obligatorio");
		$lengthValidator = new Zend_Validate_StringLength(array("max" => 200));
		$lengthValidator->setMessage("Nombre muy largo <em>(M&aacute;x: %max% caracteres)</em>");
		
		$nombreValidator->addValidator($emptyValidator)
						->addValidator($lengthValidator);
		
		$this->validators[] = $nombreValidator;
		$this->validatedFields[] = $escala->nombre;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}