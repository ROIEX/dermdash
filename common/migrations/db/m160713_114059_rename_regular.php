<?php

use yii\db\Migration;

class m160713_114059_rename_regular extends Migration
{
    public function up()
    {
        $this->renameColumn(\common\models\DoctorTreatment::tableName(), 'regular_price', 'special_price');
        $this->renameColumn(\common\models\DoctorBrand::tableName(), 'regular_price', 'special_price');
    }

    public function down()
    {
        echo "m160713_114059_rename_regular cannot be reverted.\n";

        return false;
    }
}
