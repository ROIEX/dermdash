<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_160849_body_tr_photo extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\BodyTreatment::tableName(), 'require_photo', $this->integer(2));
        $this->addColumn(\common\models\BodyTreatment::tableName(), 'photo_quantity', $this->integer());
    }

    public function down()
    {
        $this->dropColumn(\common\models\BodyTreatment::tableName(), 'require_photo');
        $this->dropColumn(\common\models\BodyTreatment::tableName(), 'photo_quantity');
    }

}
