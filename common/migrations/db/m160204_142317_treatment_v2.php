<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_142317_treatment_v2 extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\DoctorTreatment::tableName(), 'treatment_session_id', $this->integer());

        $this->createTable('{{%treatment_session}}',[
            'id'=>'pk',
            'treatment_id'=>$this->integer(),
            'session_count'=>$this->integer(),
        ]);
    }

    public function down()
    {
        echo "m160204_142317_treatment_v2 cannot be reverted.\n";

        return false;
    }
}
