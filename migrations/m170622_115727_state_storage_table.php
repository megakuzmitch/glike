<?php

use yii\db\Migration;

class m170622_115727_state_storage_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{state_storage}}', [
            'service' => $this->string(128)->notNull(),
            'state' => $this->string()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{state_storage}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170622_115727_state_storage_table cannot be reverted.\n";

        return false;
    }
    */
}
