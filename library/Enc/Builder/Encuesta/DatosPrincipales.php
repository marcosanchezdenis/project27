<?php
class Enc_Builder_Encuesta_DatosPrincipales extends Base_Builder
{
    /**
     * @var Model_EncParticular
     */
    protected $encuesta;
    
    public function __construct($data, $repository)
    {
        parent::__construct($data, $repository);
        $this->cleanData();
        $this->setActivoPorDefecto();
        $this->addIdentificador();
    }
    
    public function addIdentificador()
    {
        if (!isset($this->data["identificador"]) || $this->data["identificador"] == "") {
            $encuesta   = "enc_" . $this->data["encuesta_id"];
            $docente    = "doc_" . $this->data["docente_id"];
            $asignatura = "asig_" . $this->data["asignatura_id"];
            $periodo    = "per_" . $this->data["periodo_lectivo_id"];
            
            $identificador = implode('-', array($encuesta, $docente, $asignatura, $periodo));
            $this->data["identificador"] = md5($identificador);
        }
    }
    
    public function setActivoPorDefecto()
	{
		$this->data["activo"] = "S";
	}
    
    public function cleanData()
    {
        if ($this->data["enc_particular_id"] == "" || $this->data["enc_particular_id"] == null) {
            unset($this->data["enc_particular_id"]);
        }
    }
    
    public function getModel()
    {
        if (isset($this->data["enc_particular_id"]) && $this->data["enc_particular_id"] != "") {
            return $this->repository->findModelById($this->data["enc_particular_id"]);
        }
        return new Model_EncParticular();
    }
    
    public function generateModels()
    {
        $this->encuesta = $this->getModel();
        $this->encuesta->fromArray($this->getData());
        
        return array("status" => true);
    }
    
    public function save()
    {
        $this->encuesta->save();
        $id = $this->encuesta->get('enc_particular_id');
        return $id;
    }
}