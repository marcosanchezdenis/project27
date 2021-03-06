<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_PreguntaSm', 'doctrine');

/**
 * Model_Base_PreguntaSm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $pregunta_sm_id
 * @property integer $pregunta_id
 * @property string $nombre
 * @property integer $valor
 * @property Model_Pregunta $Pregunta
 * @property Doctrine_Collection $OpcionElegida
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_PreguntaSm extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('pregunta_sm');
        $this->hasColumn('pregunta_sm_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'pregunta_sm_pregunta_sm_id',
             ));
        $this->hasColumn('pregunta_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('nombre', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('valor', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'primary' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Pregunta as Pregunta', array(
             'local' => 'pregunta_id',
             'foreign' => 'pregunta_id'));

        $this->hasMany('Model_OpcionElegida as OpcionElegida', array(
             'local' => 'pregunta_sm_id',
             'foreign' => 'pregunta_sm_id'));
    }
}