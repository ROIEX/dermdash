<?php
/**
 * Copyright (c) 2016. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

use yii\db\Schema;
use yii\db\Migration;

class m160129_110719_new_tables extends Migration
{
    public function safeUp()
    {
        $this->execute('SET foreign_key_checks = 0;');
//fk_doctor_list_inq
        //ALTER TABLE `doctor_brand`
//        ADD CONSTRAINT `fk_item_doctor_brand` FOREIGN KEY (`sub_item_id`) REFERENCES `sub_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//  ADD CONSTRAINT `fk_user_doctor_brand` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

//        ALTER TABLE `doctor_treatment`
//  ADD CONSTRAINT `fk_doctor_treat` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//  ADD CONSTRAINT `fk_item_treatment` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

//        ALTER TABLE `photo_param`
//  ADD CONSTRAINT `fk_body_part_photo_params` FOREIGN KEY (`body_part_id`) REFERENCES `body_part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//  ADD CONSTRAINT `fk_item_photo_params` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
        $this->dropForeignKey('fk_doctor_list_inq','{{%inquiry_doctor_list}}');
        $this->dropForeignKey('fk_item_doctor_brand','{{%doctor_brand}}');
        $this->dropForeignKey('fk_item_treatment','{{%doctor_treatment}}');
        $this->dropForeignKey('fk_item_photo_params','{{%photo_param}}');
        $this->dropForeignKey('fk_add_item_att','{{%additional_attribute}}');
        $this->dropTable('{{%inquiry}}');
        $this->renameColumn('{{%additional_attribute}}','item_id','treatment_id');
        $this->dropTable('{{%item}}');
        $this->dropTable('{{%sub_item}}');
        $this->createTable("{{%treatment}}",[
            'id'=>'pk',
            'name'=>$this->string(),
            'sub_string'=>$this->text(),
            'created_at'=>$this->integer(),
            'status'=>$this->smallInteger()
        ]);
        $this->createTable('{{%brand}}',[
            'id'=>'pk',
            'name'=>$this->string(),
            'icon_path'=>$this->string(),
            'icon_base_url'=>$this->string(),
            'sub_string'=>$this->text(),
            'instruction'=>$this->text(),
            'treatment_id'=>$this->integer(),
            'created_at'=>$this->integer(),
            'status'=>$this->smallInteger(),
            'need_count'=>$this->smallInteger()
        ]);
        $this->addForeignKey('fk_treatments_brand','{{%brand}}','treatment_id','{{%treatment}}','id','cascade','cascade');

        $this->createTable('{{%brand_param}}',[
            'id'=>'pk',
            'brand_id'=>$this->integer(),
            'value'=>$this->string(),
            'status'=>$this->smallInteger()
        ]);

        $this->addForeignKey('fk_brands_params','{{%brand_param}}','brand_id','{{%brand}}','id','cascade','cascade');

        $this->createTable('{{%treatment_param}}',[
            'id'=>'pk',
            'treatment_id'=>$this->integer(),
            'value'=>$this->string()
        ]);

        $this->addForeignKey('fk_treatments_params','{{%treatment_param}}','treatment_id','{{%treatment}}','id','cascade','cascade');



        $this->addForeignKey('fk_add_treatment_attribute','{{%additional_attribute}}','treatment_id','{{%treatment}}','id','cascade','cascade');

        $this->createTable('{{%body_part_treatment}}',[
            'id'=>'pk',
            'treatment_id'=>$this->integer(),
            'body_part_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_treatment_body_part','{{%body_part_treatment}}','body_part_id','{{%body_part}}','id','cascade','cascade');
        $this->addForeignKey('fk_treatment_body_part_id','{{%body_part_treatment}}','treatment_id','{{%treatment}}','id','cascade','cascade');

        $this->createTable('{{%inquiry}}',[
            'id'=>'pk',
            'user_id'=>$this->integer(),
            'type'=>$this->smallInteger(),
            'created_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_inquiry_user','{{%inquiry}}','user_id','{{%user}}','id','cascade','cascade');
        $this->createTable('{{%inquiry_brand}}',[
            'id'=>'pk',
            'inquiry_id'=>$this->integer(),
            'brand_param_id'=>$this->integer(),
            'count'=>$this->integer()
        ]);

        $this->addForeignKey('fk_inquiry_brand','{{%inquiry_brand}}','inquiry_id','{{%inquiry}}','id','cascade','cascade');
        $this->addForeignKey('fk_inquiry_brand_param','{{%inquiry_brand}}','brand_param_id','{{%brand_param}}','id','cascade','cascade');


        $this->createTable('{{%inquiry_treatment}}',[
            'id'=>'pk',
            'inquiry_id'=>$this->integer(),
            'treatment_param_id'=>$this->integer(),
            'body_part_treatment_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_inquiry_treatment','{{%inquiry_treatment}}','inquiry_id','{{%inquiry}}','id','cascade','cascade');
        $this->addForeignKey('fk_inquiry_treatment_param','{{%inquiry_treatment}}','treatment_param_id','{{%treatment_param}}','id','cascade','cascade');
        $this->addForeignKey('fk_inquiry_body_part_treat','{{%inquiry_treatment}}','body_part_treatment_id','{{%body_part_treatment}}','id','cascade','cascade');

    }

    public function down()
    {
        echo "m160129_110719_new_tables cannot be reverted.\n";

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
