<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 */
class m170531_132457_create_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'avatar' => $this->string(),
            'user_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addForeignKey('ifk_profile_user', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('ifk_profile_user', '{{$profile}}');
        $this->dropTable('{{%profile}}');
    }
}
