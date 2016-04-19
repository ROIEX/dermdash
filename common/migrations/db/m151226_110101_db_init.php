<?php

use yii\db\Schema;

class m151226_110101_db_init extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . '(32)',
            'auth_key' => Schema::TYPE_STRING . '(32)',
            'access_token' => Schema::TYPE_STRING . '(40)',
            'password_hash' => Schema::TYPE_STRING . '(255)',
            'password_reset_token' => Schema::TYPE_STRING . '(255)',
            'oauth_client' => Schema::TYPE_STRING . '(255)',
            'oauth_client_user_id' => Schema::TYPE_STRING . '(255)',
            'email' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'logged_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%article_category}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(1024)',
            'title' => Schema::TYPE_STRING . '(512)',
            'body' => Schema::TYPE_TEXT,
            'parent_id' => Schema::TYPE_INTEGER . '(11)',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[parent_id]]) REFERENCES {{%article_category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%article}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(1024)',
            'title' => Schema::TYPE_STRING . '(512)',
            'body' => Schema::TYPE_TEXT . '',
            'view' => Schema::TYPE_STRING . '(255)',
            'category_id' => Schema::TYPE_INTEGER . '(11)',
            'thumbnail_base_url' => Schema::TYPE_STRING . '(1024)',
            'thumbnail_path' => Schema::TYPE_STRING . '(1024)',
            'author_id' => Schema::TYPE_INTEGER . '(11)',
            'updater_id' => Schema::TYPE_INTEGER . '(11)',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0',
            'published_at' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[updater_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%article_category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%article_attachment}}', [
            'id' => Schema::TYPE_PK,
            'article_id' => Schema::TYPE_INTEGER . '(11)',
            'path' => Schema::TYPE_STRING . '(255)',
            'base_url' => Schema::TYPE_STRING . '(255)',
            'type' => Schema::TYPE_STRING . '(255)',
            'size' => Schema::TYPE_INTEGER . '(11)',
            'name' => Schema::TYPE_STRING . '(255)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'order' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[article_id]]) REFERENCES {{%article}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%body_part}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(16)',
            'description' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);
        
        $this->createTable('{{%city}}', [
            'id' => Schema::TYPE_PK,
            'state_id' => Schema::TYPE_INTEGER . '(11)',
            'name' => Schema::TYPE_STRING . '(16)',
        ], $tableOptions);
        
        $this->createTable('{{%doctor}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'clinic' => Schema::TYPE_STRING . '(16)',
            'license' => Schema::TYPE_STRING . '(16)',
            'license_exp' => Schema::TYPE_DATE . '',
            'dea' => Schema::TYPE_STRING . '(16)',
            'dea_exp' => Schema::TYPE_DATE . '',
            'npi' => Schema::TYPE_STRING . '(16)',
            'experience' => Schema::TYPE_STRING . '(8)',
            'education' => Schema::TYPE_STRING . '(16)',
            'fax' => Schema::TYPE_STRING . '(16)',
            'graduation_date' => Schema::TYPE_DATE . '',
        ], $tableOptions);
        
        $this->createTable('{{%doctor_treatment}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'item_id' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%file_storage_item}}', [
            'id' => Schema::TYPE_PK,
            'component' => Schema::TYPE_STRING . '(255)',
            'base_url' => Schema::TYPE_STRING . '(1024)',
            'path' => Schema::TYPE_STRING . '(1024)',
            'type' => Schema::TYPE_STRING . '(255)',
            'size' => Schema::TYPE_INTEGER . '(11)',
            'name' => Schema::TYPE_STRING . '(255)',
            'upload_ip' => Schema::TYPE_STRING . '(15)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%i18n_source_message}}', [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING . '(32)',
            'message' => Schema::TYPE_TEXT,
        ], $tableOptions);
        
        $this->createTable('{{%i18n_message}}', [
            'id' => Schema::TYPE_INTEGER . '(11)',
            'language' => Schema::TYPE_STRING . '(16)',
            'translation' => Schema::TYPE_TEXT,
            'PRIMARY KEY ([[id]], [[language]])',
            'FOREIGN KEY ([[id]]) REFERENCES {{%i18n_source_message}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%inquiry}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'treatment_item_id' => Schema::TYPE_INTEGER . '(11)',
            'body_part_id' => Schema::TYPE_INTEGER . '(11)',
            'photo_id' => Schema::TYPE_INTEGER . '(11)',
            'doctor_list_id' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%inquiry_doctor_list}}', [
            'id' => Schema::TYPE_PK,
            'unquiry_id' => Schema::TYPE_INTEGER . '(11)',
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'comment' => Schema::TYPE_STRING . '(512)',
            'price' => Schema::TYPE_STRING . '(16)',
        ], $tableOptions);
        
        $this->createTable('{{%inquiry_photo}}', [
            'id' => Schema::TYPE_PK,
            'inquiry_id' => Schema::TYPE_INTEGER . '(11)',
            'photo_path' => Schema::TYPE_STRING . '(64)',
        ], $tableOptions);
        
        $this->createTable('{{%key_storage_item}}', [
            'key' => Schema::TYPE_STRING . '(128)',
            'value' => Schema::TYPE_TEXT . '',
            'comment' => Schema::TYPE_TEXT,
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[key]])',
        ], $tableOptions);

        $this->createTable('{{%rbac_auth_rule}}', [
            'name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);

        $this->createTable('{{%rbac_auth_item}}', [
            'name' => Schema::TYPE_STRING . '(64)',
            'type' => Schema::TYPE_INTEGER . '(11)',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[name]])',
            'FOREIGN KEY ([[rule_name]]) REFERENCES {{%rbac_auth_rule}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%rbac_auth_assignment}}', [
            'item_name' => Schema::TYPE_STRING . '(64)',
            'user_id' => Schema::TYPE_STRING . '(64)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'PRIMARY KEY ([[item_name]], [[user_id]])',
            'FOREIGN KEY ([[item_name]]) REFERENCES {{%rbac_auth_item}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable('{{%rbac_auth_item_child}}', [
            'parent' => Schema::TYPE_STRING . '(64)',
            'child' => Schema::TYPE_STRING . '(64)',
            'PRIMARY KEY ([[parent]], [[child]])',
            'FOREIGN KEY ([[child]]) REFERENCES {{%rbac_auth_item}} ([[name]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%page}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(2048)',
            'title' => Schema::TYPE_STRING . '(512)',
            'body' => Schema::TYPE_TEXT . '',
            'view' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . '(6)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%promo_code}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'text' => Schema::TYPE_STRING . '(16)',
            'value' => Schema::TYPE_STRING . '(16)',
            'is_reusable' => Schema::TYPE_SMALLINT . '(2)',
            'description' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);
        
        $this->createTable('{{%promo_used}}', [
            'id' => Schema::TYPE_PK,
            'promo_id' => Schema::TYPE_INTEGER . '(11)',
            'user_id' => Schema::TYPE_INTEGER . '(11)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%state}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(16)',
            'short_name' => Schema::TYPE_STRING . '(8)',
        ], $tableOptions);
        
        $this->createTable('{{%system_log}}', [
            'id' => Schema::TYPE_BIGPK,
            'level' => Schema::TYPE_INTEGER . '(11)',
            'category' => Schema::TYPE_STRING . '(255)',
            'log_time' => Schema::TYPE_DOUBLE,
            'prefix' => Schema::TYPE_TEXT,
            'message' => Schema::TYPE_TEXT,
        ], $tableOptions);
        
        $this->createTable('{{%timeline_event}}', [
            'id' => Schema::TYPE_PK,
            'application' => Schema::TYPE_STRING . '(64)',
            'category' => Schema::TYPE_STRING . '(64)',
            'event' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
        
        $this->createTable('{{%treatment_category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(16)',
        ], $tableOptions);
        
        $this->createTable('{{%treatment_item}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . '(11)',
            'name' => Schema::TYPE_STRING . '(32)',
            'description' => Schema::TYPE_STRING . '(128)',
        ], $tableOptions);
        
        $this->createTable('{{%user_profile}}', [
            'user_id' => Schema::TYPE_PK,
            'firstname' => Schema::TYPE_STRING . '(255)',
            'middlename' => Schema::TYPE_STRING . '(255)',
            'lastname' => Schema::TYPE_STRING . '(255)',
            'avatar_path' => Schema::TYPE_STRING . '(255)',
            'avatar_base_url' => Schema::TYPE_STRING . '(255)',
            'locale' => Schema::TYPE_STRING . '(32)',
            'gender' => Schema::TYPE_SMALLINT . '(1)',
            'date_of_birth' => Schema::TYPE_DATE . '',
            'reward' => Schema::TYPE_STRING . '(8)',
            'phone' => Schema::TYPE_STRING . '(16)',
            'address' => Schema::TYPE_STRING . '(32)',
            'zipcode' => Schema::TYPE_STRING . '(5)',
            'city_id' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%widget_carousel}}', [
            'id' => Schema::TYPE_PK,
            'key' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0',
        ], $tableOptions);
        
        $this->createTable('{{%widget_carousel_item}}', [
            'id' => Schema::TYPE_PK,
            'carousel_id' => Schema::TYPE_INTEGER . '(11)',
            'base_url' => Schema::TYPE_STRING . '(1024)',
            'path' => Schema::TYPE_STRING . '(1024)',
            'type' => Schema::TYPE_STRING . '(255)',
            'url' => Schema::TYPE_STRING . '(1024)',
            'caption' => Schema::TYPE_STRING . '(1024)',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0',
            'order' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
            'FOREIGN KEY ([[carousel_id]]) REFERENCES {{%widget_carousel}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->createTable('{{%widget_menu}}', [
            'id' => Schema::TYPE_PK,
            'key' => Schema::TYPE_STRING . '(32)',
            'title' => Schema::TYPE_STRING . '(255)',
            'items' => Schema::TYPE_TEXT . '',
            'status' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0',
        ], $tableOptions);
        
        $this->createTable('{{%widget_text}}', [
            'id' => Schema::TYPE_PK,
            'key' => Schema::TYPE_STRING . '(255)',
            'title' => Schema::TYPE_STRING . '(255)',
            'body' => Schema::TYPE_TEXT . '',
            'status' => Schema::TYPE_SMALLINT . '(6)',
            'created_at' => Schema::TYPE_INTEGER . '(11)',
            'updated_at' => Schema::TYPE_INTEGER . '(11)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%widget_text}}');
        $this->dropTable('{{%widget_menu}}');
        $this->dropTable('{{%widget_carousel_item}}');
        $this->dropTable('{{%widget_carousel}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%treatment_item}}');
        $this->dropTable('{{%treatment_category}}');
        $this->dropTable('{{%timeline_event}}');
        $this->dropTable('{{%system_log}}');
        $this->dropTable('{{%state}}');
        $this->dropTable('{{%promo_used}}');
        $this->dropTable('{{%promo_code}}');
        $this->dropTable('{{%page}}');
        $this->dropTable('{{%key_storage_item}}');
        $this->dropTable('{{%inquiry_photo}}');
        $this->dropTable('{{%inquiry_doctor_list}}');
        $this->dropTable('{{%inquiry}}');
        $this->dropTable('{{%i18n_message}}');
        $this->dropTable('{{%i18n_source_message}}');
        $this->dropTable('{{%file_storage_item}}');
        $this->dropTable('{{%doctor_treatment}}');
        $this->dropTable('{{%doctor}}');
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%body_part}}');
        $this->dropTable('{{%article_attachment}}');
        $this->dropTable('{{%article}}');
        $this->dropTable('{{%article_category}}');
        $this->dropTable('{{%user}}');
    }
}
