<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_132213_reigstration_invite extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%registration_invite}}', [
            'id' => Schema::TYPE_PK,
            'bidder_id' => Schema::TYPE_INTEGER . '(11)',
            'promo_id' => Schema::TYPE_INTEGER  . '(11)',
            'status' => Schema::TYPE_SMALLINT  . '(1)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%registration_invite}}');
    }
}
