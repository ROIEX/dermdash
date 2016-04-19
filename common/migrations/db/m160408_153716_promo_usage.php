<?php

use yii\db\Migration;

class m160408_153716_promo_usage extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\PromoUsed::tableName(), 'used_while', $this->integer(1));
    }

    public function down()
    {
        echo "m160408_153716_promo_usage cannot be reverted.\n";

        return false;
    }
}
