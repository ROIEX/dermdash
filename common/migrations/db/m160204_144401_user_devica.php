<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_144401_user_devica extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_device}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'device_type'=>$this->integer(),
            'device_token'=>$this->string(128),
            'created_at'=>$this->integer(),
        ]);
    }

    public function down()
    {
        echo "m160204_144401_user_devica cannot be reverted.\n";

        return false;
    }
}
