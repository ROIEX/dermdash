<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_102534_inquiry_photo extends Migration
{
    public function up()
    {
        $this->dropTable('{{%inquiry_photo}}');
    }

    public function down()
    {
        echo "m160216_102534_inquiry_photo cannot be reverted.\n";

        return false;
    }
}
