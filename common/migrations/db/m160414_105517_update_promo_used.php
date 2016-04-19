<?php

use yii\db\Migration;

class m160414_105517_update_promo_used extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\PromoUsed::tableName(), 'counted', $this->integer(1));
    }

    public function down()
    {
        echo "m160414_105517_update_promo_used cannot be reverted.\n";

        return false;
    }
}
