<?php

namespace common\models\query;

use common\models\User;
use Yii;


/**
 * This is the ActiveQuery class for [[\common\models\User]].
 *
 * @see \common\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function patientList()
    {
        $this->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id')
            ->where(['rbac_auth_assignment.item_name' => User::ROLE_USER])->orderBy('user.created_at DESC');
        return $this;
    }

    /**
     * @return $this
     * Returns patients list for specific doctor
     */
    public function doctorPatientList()
    {
        $this->join('LEFT JOIN', 'inquiry', 'inquiry.user_id = user.id')
            ->join('LEFT JOIN', 'inquiry_doctor_list', 'inquiry_doctor_list.inquiry_id = inquiry.id')
            ->where(['inquiry_doctor_list.user_id' => Yii::$app->user->id]);
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