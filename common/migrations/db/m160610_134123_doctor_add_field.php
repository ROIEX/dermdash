<?php

use yii\db\Migration;

class m160610_134123_doctor_add_field extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Doctor::tableName(), 'add_info', $this->string(256));
    }

    public function down()
    {
        echo "m160610_134123_doctor_add_field cannot be reverted.\n";

        return false;
    }
}
