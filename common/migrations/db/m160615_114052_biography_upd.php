<?php

use yii\db\Migration;

class m160615_114052_biography_upd extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\Doctor::tableName(), 'biography', $this->text());
    }

    public function down()
    {
        echo "m160615_114052_biography_upd cannot be reverted.\n";

        return false;
    }
}
