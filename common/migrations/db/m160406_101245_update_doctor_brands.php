<?php

use yii\db\Migration;

class m160406_101245_update_doctor_brands extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\DoctorBrand::tableName(), 'price', $this->double());
    }

    public function down()
    {
        echo "m160406_101245_update_doctor_brands cannot be reverted.\n";

        return false;
    }
}
