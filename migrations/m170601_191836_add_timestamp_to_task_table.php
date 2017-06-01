<?php

use yii\db\Migration;

class m170601_191836_add_timestamp_to_task_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%task}}', 'created_at', $this->integer());
        $this->addColumn('{{%task}}', 'updated_at', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%task}}', 'created_at');
        $this->dropColumn('{{%task}}', 'updated_at');
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
