<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_111330_bonus extends Migration
{
    public function up()
    {
        $this->createTable('{{%bonus}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'amount'=>$this->integer(),
            'status'=>$this->smallInteger(1)
        ]);
        $this->addForeignKey('fk_bonus_user','{{%bonus}}','user_id',\common\models\User::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk_bonus_user','{{%bonus}}');
        $this->dropTable('{{%bonus}}');

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
