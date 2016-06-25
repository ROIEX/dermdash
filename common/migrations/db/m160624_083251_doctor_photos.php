<?php

use yii\db\Migration;

class m160624_083251_doctor_photos extends Migration
{
    public function up()
    {
        $this->createTable('doctor_photo', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer(),
            'base_url' => $this->string(255),
            'path' => $this->string(128),
        ]);
    }

    public function down()
    {
        $this->dropTable('doctor_photo');
    }
}
