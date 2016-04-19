<?php

use yii\db\Schema;
use yii\db\Migration;

class m160217_140030_treatment_param_image extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\TreatmentParam::tableName(), 'icon_base_url', $this->string(128));
        $this->addColumn(\common\models\TreatmentParam::tableName(), 'icon_path', $this->string(128));
    }

    public function down()
    {
        echo "m160217_140030_treatment_param_image cannot be reverted.\n";

        return false;
    }
}
