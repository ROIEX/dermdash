<?php

use yii\db\Migration;

class m160310_103139_signature extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Doctor::tableName(), 'signature', $this->string(128));
    }

    public function down()
    {
        echo "m160310_103139_signature cannot be reverted.\n";

        return false;
    }
}
