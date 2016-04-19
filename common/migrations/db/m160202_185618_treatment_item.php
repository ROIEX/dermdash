<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_185618_treatment_item extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%treatment}}', 'per item', 'per_item');
    }

    public function down()
    {
        echo "m160202_185618_treatment_item cannot be reverted.\n";

        return false;
    }

}
