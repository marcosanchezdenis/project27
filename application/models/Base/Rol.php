<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_Rol', 'doctrine');

/**
 * Model_Base_Rol
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $rol_id
 * @property string $nombre
 * @property string $descripcion
 * @property string $codigo
 * @property Doctrine_Collection $UsuarioPerm
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Rol extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('rol');
        $this->hasColumn('rol_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'rol_rol_id',
             ));
        $this->hasColumn('nombre', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('descripcion', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'primary' => false,
             ));
        $this->hasColumn('codigo', 'string', null, array(
            'type' => 'string',
            'fixed' => false,
            'unsigned' => false,
            'notnull' => false,
            'primary' => false,
        ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_UsuarioPerm as UsuarioPerm', array(
             'local' => 'rol_id',
             'foreign' => 'rol_id'));
    }
}
