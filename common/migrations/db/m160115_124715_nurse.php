<?php

use yii\db\Schema;
use yii\db\Migration;

class m160115_124715_nurse extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%nurse}}', [
            'id' => Schema::TYPE_PK,
            'first_name' => Schema::TYPE_STRING . '(128)',
            'last_name' => Schema::TYPE_STRING . '(128)',
            'gender' => Schema::TYPE_SMALLINT . '(1)',
            'license' => Schema::TYPE_STRING . '(16)',
            'date_of_birth' => Schema::TYPE_DATE . '',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%nurse}}');
    }

}
