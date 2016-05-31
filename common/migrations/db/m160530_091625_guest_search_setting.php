<?php

use yii\db\Migration;

class m160530_091625_guest_search_setting extends Migration
{
    public function up()
    {
        $this->insert(\common\models\Settings::tableName(), [
            'name' => Yii::t('app', 'Guest search'),
            'value' => 3,
            'description' => Yii::t('app', 'Quantity of search results for guest search')
        ]);
    }

    public function down()
    {
        echo "m160530_091625_guest_search_setting cannot be reverted.\n";

        return false;
    }
}
