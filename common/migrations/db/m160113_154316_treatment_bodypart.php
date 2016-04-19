<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_154316_treatment_bodypart extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%body_treatment}}', [
            'id' => Schema::TYPE_PK,
            'body_part_id' => Schema::TYPE_INTEGER . '(11)',
            'treatment_item_id' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%body_treatment}}');
    }
}
