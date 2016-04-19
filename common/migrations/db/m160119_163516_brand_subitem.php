<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_163516_brand_subitem extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%dosage}}', [
            'id' => Schema::TYPE_PK,
            'value' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

        $this->createTable('{{%brand_subitem}}', [
            'id' => Schema::TYPE_PK,
            'item_id' => Schema::TYPE_INTEGER . '(11)',
            'type_count_id' => Schema::TYPE_INTEGER . '(11)',
            'body_part_id' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

        $this->createTable('{{%doctor_brand}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'subitem_id' => Schema::TYPE_INTEGER . '(11)',
            'price' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%dosage}}');
        $this->dropTable('{{%brand_subitem}}');
        $this->dropTable('{{%doctor_brand}}');
    }
}
