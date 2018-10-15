<?php
class Adm_Builder_Asignatura_DatosPrincipales extends Base_Builder
{
    /**
     * @var Model_Asignatura
     */
    protected $asignatura;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->cleanData();
        $this->setActivoPorDefault();
        $this->addCodigoAsignatura();
    }
    
    public function cleanData()
    {
        if (isset($this->data["asignatura_id"]) && $this->data["asignatura_id"] == "") {
            unset($this->data["asignatura_id"]);
        }
    }
    
    public function setActivoPorDefault()
    {
        if (isset($this->data["activo"]) && $this->data["activo"] == "") {
            $this->data["activo"] = "S";
        }
    }
    
    public function getCodigoPorDefault()
    {
        $query = new Adm_QueryObject_Asignatura_NextCodigo();
        $asignatura = $query->getDoctrineQuery()->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        if ($asignatura[0]["codigo"] == "" || $asignatura[0]["codigo"] == null) {
            $asignatura[0]["codigo"] = 0;
        }
        
        $codigoAsignatura = $asignatura[0]["codigo"] + 1;
        return "$codigoAsignatura";
    }
    
    public function addCodigoAsignatura()
    {
        if (isset($this->data["codigo"]) && $this->data["codigo"] == "") {
            $this->data["codigo"] = $this->getCodigoPorDefault();
        }
    }
    
    public function getModel()
    {
        if (isset($this->data["asignatura_id"]) && $this->data["asignatura_id"] != "") {
            return $this->repository->findModelById($this->data["asignatura_id"]);
        }
        return new Model_Asignatura();
    }
    
    public function generateModels()
    {
        $this->asignatura = $this->getModel();
        $this->asignatura->fromArray($this->getData());
        
        $asignaturaValidator = new Adm_Validator_Asignatura_DatosPrincipales();
        
        if (!$asignaturaValidator->isValid($this->asignatura)) {
            return array(
                "status" => false,
                "errors" => $asignaturaValidator->getMessages(),
            );
        }
        
        return array("status" => true);
    }
    
    public function save()
    {
        $this->asignatura->save();
    
        $id = $this->asignatura->get("asignatura_id");
        return $id;
    }
}