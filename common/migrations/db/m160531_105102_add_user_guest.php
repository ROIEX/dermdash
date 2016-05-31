<?php

use yii\db\Migration;

class m160531_105102_add_user_guest extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id' => 3,
            'username' => 'guest',
            'email' => 'guest@guest.guest',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('dermdash_guest_account'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'access_token' => Yii::$app->getSecurity()->generateRandomString(40),
            'status' => \common\models\User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert('{{%user_profile}}', [
            'user_id' => 3,
            'locale' => Yii::$app->sourceLanguage
        ]);

        $this->insert('{{%rbac_auth_assignment}}', [
            'item_name' => 'user',
            'user_id' => 3,
            'created_at' => time(),
        ]);
    }

    public function down()
    {
        echo "m160531_105102_add_user_guest cannot be reverted.\n";

        return false;
    }
}
