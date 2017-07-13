<?php

use yii\db\Migration;

class m170713_131305_add_type_to_profile_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'type', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170713_131305_add_type_to_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
