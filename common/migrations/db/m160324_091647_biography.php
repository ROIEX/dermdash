<?php

use yii\db\Migration;

class m160324_091647_biography extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Doctor::tableName(), 'biography', $this->string(1000));
    }

    public function down()
    {
        echo "m160324_091647_biography cannot be reverted.\n";

        return false;
    }
}
