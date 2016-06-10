<?php
$config = [
    'name'=>'DermDash',
    'vendorPath'=>dirname(dirname(__DIR__)).'/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'sourceLanguage'=>'en-US',
    //'timezone' => 'Europe/Berlin',
    'language'=>'en-US',
    'bootstrap' => ['log'],
    'components' => [

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%rbac_auth_item}}',
            'itemChildTable' => '{{%rbac_auth_item_child}}',
            'assignmentTable' => '{{%rbac_auth_assignment}}',
            'ruleTable' => '{{%rbac_auth_rule}}'
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache'
        ],

        'commandBus' => [
            'class' => '\trntv\tactician\Tactician',
            'commandNameExtractor' => '\League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor',
            'methodNameInflector' => '\League\Tactician\Handler\MethodNameInflector\HandleInflector',
            'commandToHandlerMap' => [
                'common\commands\command\SendEmailCommand' => '\common\commands\handler\SendEmailHandler',
                'common\commands\command\AddToTimelineCommand' => '\common\commands\handler\AddToTimelineHandler',
            ]
        ],

        'formatter'=>[
            'class'=>'yii\i18n\Formatter'
        ],

        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@storage/web/source',
            'cachePath' => '@storage/cache',
            'urlManager' => 'urlManagerStorage',
            'maxImageSize' => getenv('GLIDE_MAX_IMAGE_SIZE'),
            'signKey' => getenv('GLIDE_SIGN_KEY')
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => getenv('ADMIN_EMAIL')
            ]
        ],

        'db'=>[
            'class'=>'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'charset' => 'utf8',
            'enableSchemaCache' => YII_ENV_PROD,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'db'=>[
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except'=>['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
                    'prefix'=>function () {
                        $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
                        return sprintf('[%s][%s]', Yii::$app->id, $url);
                    },
                    'logVars'=>[],
                    'logTable'=>'{{%system_log}}'
                ]
            ],
        ],

        'i18n' => [
            'translations' => [
                'app'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                ],
                '*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath'=>'@common/messages',
                    'fileMap'=>[
                        'common'=>'common.php',
                        'backend'=>'backend.php',
                        'frontend'=>'frontend.php',
                    ],
                    'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation']
                ],
                /* Uncomment this code to use DbMessageSource
                 '*'=> [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'{{%i18n_source_message}}',
                    'messageTable'=>'{{%i18n_message}}',
                    'enableCaching' => YII_ENV_DEV,
                    'cachingDuration' => 3600,
                    'on missingTranslation' => ['\backend\modules\i18n\Module', 'missingTranslation']
                ],
                */
            ],
        ],

        'fileStorage' => [
            'class' => '\trntv\filekit\Storage',
            'baseUrl' => '@storageUrl/source',
            'filesystem' => [
                'class' => 'common\components\filesystem\LocalFlysystemBuilder',
                'path' => '@storage/web/source'
            ],
            'as log' => [
                'class' => 'common\behaviors\FileStorageLogBehavior',
                'component' => 'fileStorage'
            ]
        ],

        'keyStorage' => [
            'class' => 'common\components\keyStorage\KeyStorage'
        ],

        'urlManagerBackend' => \yii\helpers\ArrayHelper::merge(
            [
                'hostInfo' => Yii::getAlias('@backendUrl')
            ],
            require(Yii::getAlias('@backend/config/_urlManager.php'))
        ),
        'urlManagerFrontend' => \yii\helpers\ArrayHelper::merge(
            [
                'hostInfo' => Yii::getAlias('@frontendUrl')
            ],
            require(Yii::getAlias('@frontend/config/_urlManager.php'))
        ),
        'urlManagerStorage' => \yii\helpers\ArrayHelper::merge(
            [
                'hostInfo'=>Yii::getAlias('@storageUrl')
            ],
            require(Yii::getAlias('@storage/config/_urlManager.php'))
        )
    ],
    'params' => [
        'adminEmail' => getenv('ADMIN_EMAIL'),
        'robotEmail' => getenv('ROBOT_EMAIL'),
        'availableLocales'=>[
            'en-US'=>'English (US)',
            'ru-RU'=>'Русский (РФ)',
            'uk-UA'=>'Українська (Україна)',
            'es' => 'Español'
        ],
        'defaultLanguage' => 'en-US',
        'stripe'=>[
            'secretKey'=>'sk_test_7Iu1mcjlYqBKdqwxnjDxh6gC',
            'publishKey'=>'pk_test_g82bMSmbGGgyMzeciRNkHCuc',
            'currency'=>'usd'
        ],
        'company' => [
            'name' => 'DermDash',
            'address' => '520 Santa Monica Ave',
            'location' => 'Santa Monica, CA 90401',
            'phone' => '424-500-8611'
        ],
        'mailchimpApiKey'=>'2d87e7a0c9975a4382574a9528aa582b-us12',
        'zipCodeServiceApiKey' => 'lfxX5i84iqxSfXHkTvdq86aOzByzT2GeidcovlsOM0G1YnmSSzi1GsQf3oPXrvbK',
        'mandrillApiKey'=>'P2sCaW7ONIrrznODFCx3hg',
        'mailChimpDoctorList' => 'afe8fb8c0d',
        'mailChimpPasswordResetList' => 'a3287c6351',
        'mailChimpPatientList' => '06e0e9efe0',
        'yelp'=>[
            'consumerKey' => 'iFpPPkR5ZrZrOBjGPWRvYA',
            'consumerSecret' => 'foa8eUP4n7hAzfyyuv8dqT7G2WM',
            'token' => 'rZ3UsaUyzIWJRDCHciE09agz5TjhExsk',
            'tokenSecret' => 'sYj8DpdT53ca97UvM32Z6rxB73g',
            'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
        ]
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'generators' => [
            'crud' => ['class' => 'mdm\gii\generators\crud\Generator'],
            'mvc' => ['class' => 'mdm\gii\generators\mvc\Generator'],
            'migration' => ['class' => 'mdm\gii\generators\migration\Generator'],
        ]
    ];

    $config['components']['cache'] = [
        'class' => 'yii\caching\DummyCache'
    ];
}

return $config;
