<?php

use yii\db\Migration;

class m160408_130147_update_inquiry extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Inquiry::tableName(), 'is_viewed', $this->integer(1));
    }

    public function down()
    {
        echo "m160408_130147_update_inquiry cannot be reverted.\n";

        return false;
    }
}
