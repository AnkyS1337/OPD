<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%states_for_waybill}}`.
 */
class m200903_074010_create_states_for_waybill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%states_for_waybill}}', [
            'id' => $this->primaryKey(),
            'id_waybill'=>$this->integer(),
            'id_shop'=>$this->integer(),
            'id_product'=>$this->integer(),
            'price_for_one'=>$this->double(),
            'count'=>$this->integer(),
            'NPP'=>$this->integer(),
            'name_shop'=>$this->string(),
            'address'=>$this->string(),
            'type_of_payment'=>$this->integer(),
            'deleted'=>$this->boolean()->defaultValue(0),
            'returns'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%states_for_waybill}}');
    }
}
