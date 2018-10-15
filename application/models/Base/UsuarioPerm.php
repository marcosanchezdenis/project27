<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_UsuarioPerm', 'doctrine');

/**
 * Model_Base_UsuarioPerm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $usuario_perm_id
 * @property integer $usuario_id
 * @property integer $rol_id
 * @property Model_Rol $Rol
 * @property Model_Usuario $Usuario
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_UsuarioPerm extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('usuario_perm');
        $this->hasColumn('usuario_perm_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'usuario_perm_usuario_perm_id',
             ));
        $this->hasColumn('usuario_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('rol_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Rol as Rol', array(
             'local' => 'rol_id',
             'foreign' => 'rol_id'));

        $this->hasOne('Model_Usuario as Usuario', array(
             'local' => 'usuario_id',
             'foreign' => 'usuario_id'));
    }
}