<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_154246_severity_price extends Migration
{
    public function up()
    {
        $this->renameColumn(\common\models\TreatmentParamSeverity::tableName(),'brand_id','brand_param_id');
    }

    public function down()
    {
        echo "m160209_154246_severity_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
