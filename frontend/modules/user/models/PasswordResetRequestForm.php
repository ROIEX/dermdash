<?php
namespace frontend\modules\user\models;

use common\commands\command\SendEmailCommand;
use common\components\Mandrill;
use Yii;
use common\models\User;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no user with such email.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $user->generatePasswordResetToken();
            if ($user->save(false)) {
            $mandrill = new Mandrill(Yii::$app->params['mandrillApiKey']);
                $message = [
                    'to' => [
                        [
                            'email' => $user->email,
                            'name' => $user->email
                        ]
                    ],
                    'merge_vars' => [
                        [
                            'rcpt' => $user->email,
                            'vars' => [
                                [
                                    'name' => 'link',
                                    'content' => Html::encode(Yii::$app->urlManager->createAbsoluteUrl(['/user/sign-in/reset-password', 'token' => $user->password_reset_token])),
                                ],
                                [
                                    'name' => 'list_address_html',
                                    'content' => getenv('ADMIN_EMAIL'),
                                ],
                                [
                                    'name' => 'current_year',
                                    'content' => date('Y'),
                                ],
                                [
                                    'name' => 'company',
                                    'content' => Yii::$app->name,
                                ],
                            ]
                        ]
                    ],
                ];
                $result = $mandrill->messages->sendTemplate('Password Reset', [] , $message);
                return $result;
            }
        }

        return false;
    }

    public function attributeLabels()
    {
        return [
            'email'=>Yii::t('frontend', 'E-mail')
        ];
    }
}
