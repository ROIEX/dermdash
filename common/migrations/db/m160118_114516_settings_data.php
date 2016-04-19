<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\Settings;

class m160118_114516_settings_data extends Migration
{
    public function up()
    {
        $this->insert('{{%settings}}', [
            'id' => Settings::INQUIRY_DOCTOR_QUANTITY_ID,
            'name' => Yii::t('app', 'Inquiry doctor quantity'),
            'value' => 3,
            'description' => Yii::t('app', 'The quantity of doctors who will get the inquiry')
        ]);

        $this->insert('{{%settings}}', [
            'id' => Settings::REGISTRATION_BIDDER_PROMO_VALUE_ID,
            'name' => Yii::t('app', 'Promo bidder reward quantity'),
            'value' => 20,
            'description' => Yii::t('app', 'The quantity of reward for the person who invited someone')
        ]);

        $this->insert('{{%settings}}', [
            'id' => Settings::REGISTRATION_RECEIVER_PROMO_VALUE_ID,
            'name' => Yii::t('app', 'Promo receiver reward quantity'),
            'value' => 20,
            'description' => Yii::t('app', 'The quantity of reward for the person who received an invitation')
        ]);
    }

    public function down()
    {
        $this->delete('{{%settings}}', [
            'id' => [Settings::INQUIRY_DOCTOR_QUANTITY_ID, Settings::REGISTRATION_BIDDER_PROMO_VALUE_ID, Settings::REGISTRATION_RECEIVER_PROMO_VALUE_ID]
        ]);
        $this->execute("ALTER TABLE settings AUTO_INCREMENT = 1");
    }
}
