<?php

use yii\db\Migration;

class m170602_091533_change_identity_id_column_type_service_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%service}}', 'identity_id', $this->string()->notNull());
    }

    public function down()
    {
        $this->alterColumn('{{%service}}', 'identity_id', $this->integer()->notNull());
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
