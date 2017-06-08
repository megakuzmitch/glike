<?php

use yii\db\Migration;

class m170608_084313_add_column_item_id_to_task_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%task}}', 'item_id', $this->string());
        $this->addColumn('{{%task}}', 'owner_id', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%task}}', 'owner_id');
        $this->dropColumn('{{%task}}', 'item_id');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
