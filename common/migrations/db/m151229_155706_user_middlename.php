<?php

use yii\db\Schema;
use yii\db\Migration;

class m151229_155706_user_middlename extends Migration
{
    public function up()
    {
        $this->dropColumn(\common\models\UserProfile::tableName(),'middlename');
    }

    public function down()
    {
        $this->addColumn(\common\models\UserProfile::tableName(),'middlename',$this->string(255));
    }
}
