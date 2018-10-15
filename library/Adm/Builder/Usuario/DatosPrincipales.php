<?php
class Adm_Builder_Usuario_DatosPrincipales extends Base_Builder
{
    /**
     * @var Model_Usuario
     */
    protected $usuario;
    protected $contrasena;
    protected $method;
    
    protected $deletedData = array();
    protected $usedData = array();
    
    public function __construct($data, $repository, $method)
    {
        parent::__construct($data, $repository);
        $this->method = $method;
        $this->setNoActivoPorDefault();
        $this->setEsDocente();
    }
    
    public function setEsDocente()
    {
        if (isset($this->data["es_docente"])) {
            $this->data["es_docente"] = "S";
        } else {
            $this->data["es_docente"] = "N";
        }
    }
    
    public function setNoActivoPorDefault()
    {
        if (isset($this->data["activo"]) && $this->data["activo"] == "") {
            $this->data["activo"] = "N";
        }
    }
    
    public function getData($type)
    {
        $data = parent::getData();
        $newData = array();
        
        if ($type === "usuario") {
            if ($this->method == "post") {
                $keys = array("usuario", "contrasena", "activo");
            } else {
                $keys = array("usuario", "activo");
            }
        } else {
            $keys = array("nombre", "apellido", "email", "es_docente");
        }
        
        for ($i=0; $i<count($keys); $i++) {
            $newData[$keys[$i]] = $data[$keys[$i]];
        }
        
        return $newData;
    }
    
    public function getModel()
    {
        if (isset($this->data["usuario_id"]) && $this->data["usuario_id"] != "") {
			$usuario = $this->repository->findModelById($this->data["usuario_id"]);
			if (count($usuario->Persona->Docente) > 0) {
			    $this->data["es_docente"] = "S";
			}
			return $usuario;
		}
		return new Model_Usuario();
    }
    
    public function updatePermisos()
    {
        if ($this->method === "post") {
            if (isset($this->data['roles'])) {
                foreach ($this->data['roles'] as $rol_id) {
                    $this->usuario->UsuarioPerm[]->rol_id = $rol_id;
                }
            }
        } else {
            $rolesOld = array_map(function ($permiso) {
                return array(
                    'usuario_perm_id' => $permiso['usuario_perm_id'],
                    'rol_id' => $permiso['rol_id']
                );
            }, $this->usuario->UsuarioPerm->getData());
            
            if (isset($this->data['roles'])) {
                
                for ($i=0; $i<count($rolesOld); $i++) {
                    if (!in_array($rolesOld[$i]['rol_id'], $this->data['roles'])) {
                        $this->deletedData[] = $rolesOld[$i]['usuario_perm_id'];
                    } else {
                        $this->usedData[] = $rolesOld[$i]['rol_id'];
                    }
                }
                
                for ($i=0; $i<count($this->data['roles']); $i++) {
                    if (!in_array($this->data['roles'][$i], $this->usedData)) {
                        $this->usuario->UsuarioPerm[]->rol_id = $this->data['roles'][$i];
                    }
                }
                
            } else {
                $this->deletedData = array_map(function ($rol) {
                    return $rol['usuario_perm_id'];
                }, $rolesOld);
            }
        }
    }
    
    public function crearDocente()
    {
        if ($this->data['es_docente'] == "S") {
            if (count($this->usuario->Persona->Docente) == 0) {
                $docente = new Model_Docente();
                $docente->codigo = $docente->addCodigo();
                $docente->activo = "N";
                $this->usuario->Persona->Docente[] = $docente;
            }
        }
    }
    
    public function generateModels()
    {
        $this->usuario = $this->getModel();
        $this->usuario->fromArray($this->getData("usuario"));
        $this->usuario->Persona->fromArray($this->getData("persona"));
        
        $this->crearDocente();
        $this->updatePermisos();
        
        $usuarioValidator = new Adm_Validator_Usuario_DatosPrincipales();
        $personaValidator = new Adm_Validator_Persona();
        
        if (!$usuarioValidator->isValid($this->usuario) || !$personaValidator->isValid($this->usuario->Persona)) {
            $usuarioErrors = $usuarioValidator->getMessages();
            $personaErrors = $personaValidator->getMessages();
            	
            return array(
                "status" => false,
                "errors" => array_merge($usuarioErrors, $personaErrors),
            );
        }
        
        if ($this->method === "post") {
            $this->usuario->contrasena = sha1($this->usuario->contrasena);
        }
        
        return array("status" => true);
    }
    
    public function save()
    {
        $this->usuario->save();
        $id = $this->usuario->get('usuario_id');
        
        if (count($this->deletedData) > 0) {
            $this->deletePermisos();
        }
        
        return $id;
    }
    
    public function deletePermisos()
    {
        $query = Doctrine_Query::create()
             ->delete('Model_UsuarioPerm')
             ->whereIn('usuario_perm_id', $this->deletedData);
        $numDeleted = $query->execute();
    }
}