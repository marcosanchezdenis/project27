<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Model_OpcionElegida', 'doctrine');

/**
 * Model_Base_OpcionElegida
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $opcion_elegida_id
 * @property integer $pregunta_sm_id
 * @property integer $resp_detalle_id
 * @property Model_RespDetalle $RespDetalle
 * @property Model_PreguntaSm $PreguntaSm
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_OpcionElegida extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('opcion_elegida');
        $this->hasColumn('opcion_elegida_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'opcion_elegida_opcion_elegida_id',
             ));
        $this->hasColumn('pregunta_sm_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'primary' => false,
             ));
        $this->hasColumn('resp_detalle_id', 'integer', 4, array(
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
        $this->hasOne('Model_RespDetalle as RespDetalle', array(
             'local' => 'resp_detalle_id',
             'foreign' => 'resp_detalle_id'));

        $this->hasOne('Model_PreguntaSm as PreguntaSm', array(
             'local' => 'pregunta_sm_id',
             'foreign' => 'pregunta_sm_id'));
    }
}