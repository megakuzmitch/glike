<?php

use yii\db\Migration;

class m170601_190856_add_column_to_task_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%task}}', 'preview', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%task}}', 'preview');
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
