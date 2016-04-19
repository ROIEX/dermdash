<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_125124_inquiry extends Migration
{
    public function up()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->dropTable('{{%inquiry}}');
        $this->createTable('{{%inquiry}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'sub_item_id'=>$this->integer(),
            'count'=>$this->integer(),
            'add_item_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_user_inquiry','{{%inquiry}}','user_id','{{%user}}','id','cascade','cascade');
        $this->addForeignKey('fk_sub_item_id','{{%inquiry}}','sub_item_id','{{%sub_item}}','id','cascade','cascade');
        $this->addForeignKey('fk_add_item_id','{{%inquiry}}','add_item_id','{{%add_attribute_item}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m160127_125124_inquiry cannot be reverted.\n";

        return false;
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
