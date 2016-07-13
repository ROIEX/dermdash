<?php

use yii\db\Migration;

class m160712_141222_new_price extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\DoctorTreatment::tableName(), 'regular_price', $this->double());
        $this->addColumn(\common\models\DoctorBrand::tableName(), 'regular_price', $this->double());
    }

    public function down()
    {
        echo "m160712_141222_new_price cannot be reverted.\n";
        return false;
    }
}
