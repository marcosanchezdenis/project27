<?php
class Ins_Builder_Carrera_DatosPrincipales extends Base_Builder
{
    /**
     * @var Model_Carrera
     */
    protected $carrera;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->cleanData();
    }
    
    public function cleanData()
    {
        if (isset($this->data["carrera_id"]) && $this->data["carrera_id"] == "") {
            unset($this->data["carrera_id"]);
        }
    }
    
    public function getModel()
    {
        if (isset($this->data["carrera_id"]) && $this->data["carrera_id"] != "") {
            return $this->repository->findModelById($this->data["carrera_id"]);
        }
        return new Model_Carrera();
    }
    
    public function generateModels()
    {
        $this->carrera = $this->getModel();
        $this->carrera->fromArray($this->getData());
        
        $carreraValidator = new Ins_Validator_Carrera_DatosPrincipales();
        
        if (!$carreraValidator->isValid($this->carrera)) {
            return array(
                "status" => false,
                "errors" => $carreraValidator->getMessages(),
            );
        }
        
        return array("status" => true);
    }
    
    public function save()
    {
        $this->carrera->save();
    
        $id = $this->carrera->get("carrera_id");
        return $id;
    }
}