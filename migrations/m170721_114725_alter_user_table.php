<?php

use yii\db\Migration;

class m170721_114725_alter_user_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string(25)->null());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string(25)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_114725_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
