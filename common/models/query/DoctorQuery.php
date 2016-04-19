<?php

namespace common\models\query;
use common\components\StatusHelper;
use common\models\User;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\common\models\Doctor]].
 *
 * @see \common\models\Doctor
 */
class DoctorQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere(['status' => StatusHelper::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * @return $this
     */
    public function not_deleted()
    {
        $this->join('LEFT JOIN', 'user as user', 'user.id = doctor.user_id')->andWhere(['user.status' => User::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \common\models\Doctor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Doctor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}