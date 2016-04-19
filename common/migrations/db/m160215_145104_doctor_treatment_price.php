<?php

use yii\db\Schema;
use yii\db\Migration;

class m160215_145104_doctor_treatment_price extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%doctor_treatment}}', 'price', $this->double());
    }

    public function down()
    {
        echo "m160215_145104_doctor_treatment_price cannot be reverted.\n";

        return false;
    }
}
