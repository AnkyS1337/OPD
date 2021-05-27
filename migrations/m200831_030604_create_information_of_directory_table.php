<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%information_of_directory}}`.
 */
class m200831_030604_create_information_of_directory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%information_of_directory}}', [
            'id' => $this->primaryKey(),
            'id_directory'=>$this->integer(),
            'id_shop'=>$this->integer(),
            'id_product'=>$this->integer(),
            'price_for_one'=>$this->double(),
            'count'=>$this->integer(),
            'deleted'=>$this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%information_of_directory}}');
    }
}
