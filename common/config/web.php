<?php
$config = [
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => true,
            'appendTimestamp' => YII_ENV_DEV
        ],
        //Encrypter
        'encrypter'=>[
            'class'=>'\nickcv\encrypter\components\Encrypter',
            'globalPassword'=>'123456',
            'iv'=>'1234561231234561',
            'useBase64Encoding'=>true,
            'use256BitesEncoding'=>false,
        ]
    ],
    'as locale' => [
        'class' => 'common\behaviors\LocaleBehavior',
        'enablePreferredLanguage' => false
    ]
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.1', '172.17.42.1'],
    ];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.1', '172.17.42.1'],
    ];
}

return $config;
