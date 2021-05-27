<?php

use yii\db\Migration;

/**
 * Class m201022_111623_change_type_of_columns
 */
class m201022_111623_change_type_of_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%states_for_waybill}}', 'count', $this->double());
        $this->alterColumn('{{%states_for_waybill}}', 'returns', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201022_111623_change_type_of_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201022_111623_change_type_of_columns cannot be reverted.\n";

        return false;
    }
    */
}
