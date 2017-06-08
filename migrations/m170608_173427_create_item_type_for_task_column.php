<?php

use yii\db\Migration;

class m170608_173427_create_item_type_for_task_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'item_type', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'item_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170608_173427_create_item_type_for_task_column cannot be reverted.\n";

        return false;
    }
    */
}
