<?php

use yii\db\Migration;

class m160516_072627_create_treatment_app_name extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Treatment::tableName(), 'app_name', $this->string(128));
    }

    public function down()
    {
        $this->dropColumn(\common\models\Treatment::tableName(), 'app_name');
    }
}
