<?php

use yii\db\Migration;

class m160713_143424_doctor_list_special_price extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryDoctorList::tableName(), 'special_price', $this->double());
    }

    public function down()
    {
        echo "m160713_143424_doctor_list_special_price cannot be reverted.\n";

        return false;
    }
}
