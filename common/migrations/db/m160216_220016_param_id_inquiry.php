<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_220016_param_id_inquiry extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\InquiryDoctorList::tableName(),'param_id',$this->integer());
    }

    public function down()
    {
        echo "m160216_220016_param_id_inquiry cannot be reverted.\n";

        return false;
    }
}
