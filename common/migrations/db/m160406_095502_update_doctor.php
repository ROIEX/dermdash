<?php

use yii\db\Migration;

class m160406_095502_update_doctor extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\Doctor::tableName(), 'clinic', $this->string(128));
    }

    public function down()
    {
        echo "m160406_095502_update_doctor cannot be reverted.\n";

        return false;
    }
}
