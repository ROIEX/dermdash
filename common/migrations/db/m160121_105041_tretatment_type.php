<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_105041_tretatment_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%treatment_type}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);

        $this->createTable('{{%treatment_type_count}}', [
            'id' => Schema::TYPE_PK,
            'value' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

        $this->createTable('{{%treatment_count}}', [
            'id' => Schema::TYPE_PK,
            'treatment_id' => Schema::TYPE_INTEGER . '(11)',
            'value_id' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);

        $this->addColumn('{{%inquiry}}', 'type_count_id', $this->integer());
    }

    public function down()
    {
        $this->dropTable('{{%treatment_type}}');
        $this->dropColumn('{{%inquiry}}', 'type_count_id');
    }
}
