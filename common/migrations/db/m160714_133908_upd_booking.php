<?php

use yii\db\Migration;

class m160714_133908_upd_booking extends Migration
{
    public function up()
    {
        $this->renameColumn(\common\models\Booking::tableName(), 'lst_name', 'last_name');
    }

    public function down()
    {
        echo "m160714_133908_upd_booking cannot be reverted.\n";

        return false;
    }
}
