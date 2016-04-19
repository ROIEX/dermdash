<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_162732_brand_count extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%brand}}', 'need_count');
    }

    public function down()
    {
        echo "m160210_162732_brand_count cannot be reverted.\n";

        return false;
    }

}
