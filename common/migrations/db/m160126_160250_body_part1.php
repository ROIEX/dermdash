<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_160250_body_part1 extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_treatment_category','{{%treatment_item}}');
        $this->dropColumn('{{%body_part}}','require_photo');
        $this->dropColumn('{{%body_part}}','photo_quantity');
        $this->dropTable('{{%body_treatment}}');
        $this->dropTable('{{%dosage}}');
        $this->dropTable('{{%treatment_category}}');
        $this->dropTable('{{%treatment_count}}');
        $this->dropTable('{{%treatment_item}}');
        $this->dropTable('{{%treatment_type}}');
        $this->dropTable('{{%treatment_type_count}}');
    }

    public function down()
    {
        $this->addColumn('{{%body_part}}','require_photo',$this->integer());
        $this->addColumn('{{%body_part}}','photo_quantity',$this->integer());
    }
}
