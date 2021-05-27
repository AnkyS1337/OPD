<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%waybill}}`.
 */
class m200903_043246_create_waybill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%waybill}}', [
            'id' => $this->primaryKey(),
            'id_directory'=>$this->integer(),
            'id_driver'=>$this->integer(),
            'id_route'=>$this->integer(),
            'date'=>$this->date(),
            'deleted'=>$this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%waybill}}');
    }
}
