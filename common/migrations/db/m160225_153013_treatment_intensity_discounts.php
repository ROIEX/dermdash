<?php

use yii\db\Migration;

class m160225_153013_treatment_intensity_discounts extends Migration
{
    public function up()
    {
        $this->createTable("{{%treatment_intensity_discounts}}",[
            'id' => 'pk',
            'user_id' => $this->integer(),
            'treatment_id' => $this->integer(),
            'session_id' => $this->integer(),
            'discount_value' => $this->float(),
        ]);
    }

    public function down()
    {
        echo "m160225_153013_treatment_intensity_discounts cannot be reverted.\n";

        return false;
    }
}
