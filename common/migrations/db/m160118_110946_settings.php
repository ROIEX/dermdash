<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_110946_settings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%settings}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(128)',
            'value' => Schema::TYPE_INTEGER  . '(11)',
            'description' => Schema::TYPE_STRING . '(256)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');
    }

}
