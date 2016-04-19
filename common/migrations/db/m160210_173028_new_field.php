<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_173028_new_field extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\TreatmentParamSeverity::tableName(),'icon_path',$this->text());
        $this->addColumn(\common\models\TreatmentParamSeverity::tableName(),'icon_url',$this->text());
    }

    public function down()
    {
        echo "m160210_173028_new_field cannot be reverted.\n";

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
