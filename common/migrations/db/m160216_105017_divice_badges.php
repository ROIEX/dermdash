<?php

use yii\db\Migration;

class m160216_105017_divice_badges extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\UserDevice::tableName(),'badge',$this->integer());
    }

    public function down()
    {
        echo "m160216_105017_divice_badges cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
