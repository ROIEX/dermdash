<?php

use yii\db\Migration;

class m160610_125004_payment_initials extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Payment::tableName(), 'first_name', $this->string(64));
        $this->addColumn(\common\models\Payment::tableName(), 'last_name', $this->string(64));
    }

    public function down()
    {
        echo "m160610_125004_payment_initials cannot be reverted.\n";

        return false;
    }

}
