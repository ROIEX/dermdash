<?php

use yii\db\Migration;

class m160406_123014_setting_inser_fee extends Migration
{
    public function up()
    {
        $this->insert(\common\models\Settings::tableName(), [
            'name' => Yii::t('app', 'Payment fee'),
            'value' => 18,
            'description' => Yii::t('app', 'Fee value during payment')
        ]);
    }

    public function down()
    {
        echo "m160406_123014_setting_inser_fee cannot be reverted.\n";

        return false;
    }
}
