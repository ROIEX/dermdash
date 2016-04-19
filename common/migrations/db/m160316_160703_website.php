<?php

use yii\db\Migration;

class m160316_160703_website extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Doctor::tableName(), 'website', $this->string(128));
    }

    public function down()
    {
        echo "m160316_160703_website cannot be reverted.\n";

        return false;
    }

}
