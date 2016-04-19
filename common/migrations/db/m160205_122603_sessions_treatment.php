<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_122603_sessions_treatment extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_inquiry_body_part_treat','{{%inquiry_treatment}}');
        $this->renameColumn('{{%inquiry_treatment}}','additional_attribute_id','session_id');
        $this->addForeignKey('fk_session_inquiry','{{%inquiry_treatment}}','session_id','{{%treatment_session}}','id','cascade','cascade');
    }

    public function down()
    {
        echo "m160205_122603_sessions_treatment cannot be reverted.\n";

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
