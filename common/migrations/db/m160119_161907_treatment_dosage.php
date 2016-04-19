<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_161907_treatment_dosage extends Migration
{
    public function up()
    {
        $this->addColumn('{{%treatment_item}}', 'treatment_type_id', $this->integer());
        $this->addColumn('{{%treatment_item}}', 'treatment_dosage_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%treatment_item}}', 'treatment_type_id');
        $this->dropColumn('{{%treatment_item}}', 'treatment_dosage_id');
    }
}
