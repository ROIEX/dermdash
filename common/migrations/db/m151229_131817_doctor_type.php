<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_131817_doctor_type extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Doctor::tableName(),'doctor_type',$this->smallInteger());
    }

    public function down()
    {
        $this->dropColumn(\common\models\Doctor::tableName(),'doctor_type');
    }
}
