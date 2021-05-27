<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_of_products}}`.
 */
class m201019_131650_create_order_of_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_of_products}}', [
            'id' => $this->primaryKey(),
            'id_product'=>$this->integer(),
            'order'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_of_products}}');
    }
}
