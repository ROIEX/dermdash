<?php

use yii\db\Migration;

class m160408_130601_update_inquiry_admin extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Inquiry::tableName(), 'is_viewed_by_admin', $this->integer(1));
    }

    public function down()
    {
        echo "m160408_130601_update_inquiry_admin cannot be reverted.\n";

        return false;
    }
}
