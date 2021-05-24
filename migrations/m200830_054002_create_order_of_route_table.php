<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_of_route}}`.
 */
class m200830_054002_create_order_of_route_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_of_route}}', [
            'id' => $this->primaryKey(),
            'id_route'=>$this->integer(),
            'id_shop'=>$this->integer(),
            'NPP'=>$this->integer(),
            'deleted'=>$this->integer()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_of_route}}');
    }
}
