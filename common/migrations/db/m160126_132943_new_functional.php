<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_132943_new_functional extends Migration
{
    public function up()
    {
        $this->createTable('{{%item}}',[
            'id'=>'pk',
            'name'=>$this->string(),
            'status'=>$this->smallInteger(),
            'description'=>$this->text(),
            'short_description'=>$this->text(),
            'type'=>$this->smallInteger(1)
        ]);

        $this->createTable('{{%sub_item}}',[
            'id'=>'pk',
            'item_id'=>$this->integer(),
            'per'=>$this->string(),
            'require_count_flag'=>$this->smallInteger(1),
            'status'=>$this->smallInteger()
        ]);

        $this->addForeignKey('fk_sub_item','{{%sub_item}}','item_id','{{%item}}','id','cascade','cascade');

        $this->dropTable('{{%doctor_brand}}');
        $this->createTable('{{%doctor_brand}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'sub_item_id'=>$this->integer(),
            'price'=>$this->integer()
        ]);

        $this->addForeignKey('fk_user_doctor_brand','{{%doctor_brand}}','user_id','{{%user}}','id','cascade','cascade');
        $this->addForeignKey('fk_item_doctor_brand','{{%doctor_brand}}','sub_item_id','{{%sub_item}}','id','cascade','cascade');


        $this->addColumn('{{%additional_attribute}}','item_id',$this->integer());
        $this->addColumn('{{%additional_attribute}}','description',$this->text());

        $this->addForeignKey('fk_add_item_att','{{%additional_attribute}}','item_id','{{%item}}','id','cascade','cascade');


        $this->createTable('{{%photo_param}}',[
            'id'=>'pk',
            'item_id'=>$this->integer(),
            'count'=>$this->integer(),
            'body_part_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_item_photo_params','{{%photo_param}}','item_id','{{%item}}','id','cascade','cascade');
        $this->addForeignKey('fk_body_part_photo_params','{{%photo_param}}','body_part_id','{{%body_part}}','id','cascade','cascade');

        $this->dropForeignKey('fk_item_treat','{{%doctor_treatment}}');

        $this->addForeignKey('fk_item_treatment','{{%doctor_treatment}}','item_id','{{%item}}','id','cascade','cascade');


    }

    public function down()
    {
        $this->dropTable('{{%item}}');
        $this->dropForeignKey('fk_sub_item','{{%sub_item}}');
        $this->dropTable('{{%sub_item}}');
        $this->dropForeignKey('fk_user_doctor_brand','{{%doctor_brand}}');
        $this->dropForeignKey('fk_item_doctor_brand','{{%doctor_brand}}');
        $this->dropTable('{{%doctor_brand}}');
        $this->dropForeignKey('fk_add_item_att','{{%additional_attribute}}');
        $this->dropColumn('{{%additional_attribute}}','item_id');
        $this->dropColumn('{{%additional_attribute}}','description');
    }

}
