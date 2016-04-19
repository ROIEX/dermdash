<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_105011_body_part extends Migration
{
    public function up()
    {
        $this->dropColumn(\common\models\BodyTreatment::tableName(), 'require_photo');
        $this->dropColumn(\common\models\BodyTreatment::tableName(), 'photo_quantity');

        $this->addColumn(\common\models\BodyPart::tableName(), 'require_photo', $this->integer(2));
        $this->addColumn(\common\models\BodyPart::tableName(), 'photo_quantity', $this->integer());
    }

    public function down()
    {
        $this->addColumn(\common\models\BodyTreatment::tableName(), 'require_photo', $this->integer(2));
        $this->addColumn(\common\models\BodyTreatment::tableName(), 'photo_quantity', $this->integer());

        $this->dropColumn(\common\models\BodyPart::tableName(), 'require_photo');
        $this->dropColumn(\common\models\BodyPart::tableName(), 'photo_quantity');
    }
}
