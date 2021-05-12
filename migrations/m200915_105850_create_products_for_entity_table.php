<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products_for_entity}}`.
 */
class m200915_105850_create_products_for_entity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products_for_entity}}', [
            'id' => $this->primaryKey(),
            'id_entity'=>$this->integer(),
            'id_product'=>$this->integer(),
            'price'=>$this->float(),
            'deleted'=>$this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products_for_entity}}');
    }
}
