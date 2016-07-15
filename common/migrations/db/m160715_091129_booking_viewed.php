<?php

use yii\db\Migration;

class m160715_091129_booking_viewed extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Booking::tableName(), 'is_viewed', $this->smallInteger());
        $this->addColumn(\common\models\Booking::tableName(), 'is_viewed_admin', $this->smallInteger());
    }

    public function down()
    {
        echo "m160715_091129_booking_viewed cannot be reverted.\n";

        return false;
    }
}
