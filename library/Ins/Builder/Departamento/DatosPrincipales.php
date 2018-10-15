<?php
class Ins_Builder_Departamento_DatosPrincipales extends Base_Builder
{
    /**
     * @var Model_Departamento
     */
    protected $departamento;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->cleanData();
    }
    
    public function cleanData()
    {
        if (isset($this->data["departamento_id"]) && $this->data["departamento_id"] == "") {
            unset($this->data["departamento_id"]);
        }
    }
    
    public function getModel()
    {
        if (isset($this->data["departamento_id"]) && $this->data["departamento_id"] != "") {
            return $this->repository->findModelById($this->data["departamento_id"]);
        }
        return new Model_Departamento();
    }
    
    public function generateModels()
    {
        $this->departamento = $this->getModel();
        $this->departamento->fromArray($this->getData());
        
        $departamentoValidator = new Ins_Validator_Departamento_DatosPrincipales();
        
        if (!$departamentoValidator->isValid($this->departamento)) {
            return array(
                "status" => false,
                "errors" => $departamentoValidator->getMessages(),
            );
        }
        
        return array("status" => true);
    }
    
    public function save()
    {
        $this->departamento->save();
    
        $id = $this->departamento->get("departamento_id");
        return $id;
    }
}