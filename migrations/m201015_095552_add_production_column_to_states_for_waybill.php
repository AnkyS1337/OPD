<?php

use yii\db\Migration;

/**
 * Class m201015_095552_add_production_column_to_states_for_waybill
 */
class m201015_095552_add_production_column_to_states_for_waybill extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%states_for_waybill}}', 'count_for_production', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201015_095552_add_production_column_to_states_for_waybill cannot be reverted.\n";

        return false;
    }
    */
}
