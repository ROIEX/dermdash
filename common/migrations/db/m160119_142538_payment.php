<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_142538_payment extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'payment_id'=>$this->string(),
            'created_at'=>$this->integer(),
            'paid'=>$this->smallInteger(1),
            'status'=>$this->string(),
            'amount'=>$this->integer()
        ]);
        $this->addForeignKey('fk_user_payment','{{%payment}}','user_id',\common\models\User::tableName(),'id','cascade','cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_payment','{{%payment}}');
        $this->dropTable('{{%payment}}');
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
