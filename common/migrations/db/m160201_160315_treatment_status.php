<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_160315_treatment_status extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\TreatmentParam::tableName(), 'status', $this->integer(2));
        $this->addColumn(\common\models\Treatment::tableName(), 'per item', $this->integer(2));
    }

    public function down()
    {
        echo "m160201_160315_treatment_status cannot be reverted.\n";

        return false;
    }
}
