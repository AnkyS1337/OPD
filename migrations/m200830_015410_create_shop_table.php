<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop}}`.
 */
class m200830_015410_create_shop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop}}', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(),
            'address'=>$this->string(),
            'payment_method'=>$this->boolean(),
            'deleted'=>$this->boolean()->defaultValue(0),
            'coord' =>$this->string(),
            'id_entity' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop}}');
    }
}
