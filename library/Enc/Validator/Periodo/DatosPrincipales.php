<?php
class Enc_Validator_Periodo_DatosPrincipales extends Zend_Validate_Abstract
{
	protected $validators 		= array();
	protected $validatedFields  = array();
	protected $validationErrors = array();
	
	public function validateFields($periodo)
	{
		$this->validatePeriodo($periodo);
	}
	
	public function isValid($periodo)
	{
		$this->validateFields($periodo);
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
	
	public function validatePeriodo($periodo)
	{
		$periodoValidator = new Zend_Validate();
		$emptyValidator = new Zend_Validate_NotEmpty();
		$emptyValidator->setMessage("El campo <b>'ANHO LECTIVO'</b> es obligatorio");
		$betweenValidator = new Zend_Validate_Between(array('min' => 1, 'max' => 9999));
		$betweenValidator->setMessage("A&ntilde;o Invalido <em>(A&ntilde; de 4 digitos)</em>");
		
		$periodoValidator->addValidator($emptyValidator)
						 ->addValidator($betweenValidator);
		
		$this->validators[] = $periodoValidator;
		$this->validatedFields[] = $periodo->anho_lectivo;
	}
	
	public function getMessages()
	{
		return $this->validationErrors;
	}
}