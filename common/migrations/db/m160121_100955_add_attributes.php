<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_100955_add_attributes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%additional_attribute}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);

        $this->createTable('{{%add_attribute_item}}', [
            'id' => Schema::TYPE_PK,
            'attribute_id' => Schema::TYPE_INTEGER . '(11)',
            'value' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);

        $this->createTable('{{%attribute_treatment_item}}', [
            'id' => Schema::TYPE_PK,
            'attribute_id' => Schema::TYPE_INTEGER . '(11)',
            'treatment_item_id' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

        $this->addColumn('{{%inquiry}}', 'add_attribute_id', $this->integer());
    }

    public function down()
    {
        $this->dropTable('{{%additional_attribute}}');
        $this->dropTable('{{%add_attribute_item}}');
        $this->dropTable('{{%attribute_treatment_item}}');
        $this->dropColumn('{{%inquiry}}', 'add_attribute');
    }
}
