<?php

use yii\db\Migration;

class m160428_141906_drop_tables extends Migration
{
    public function up()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->dropTable('nurse');
        $this->dropTable('body_part_treatment');
        $this->dropTable('body_part');
        $this->dropTable('photo_param');
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');
    }

}
