<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'Цифровой портал',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    //'aliases' => require(__DIR__ . '/bootstrap.php'),
    'components' => [
        'user' => [
            //'identityClass' => 'common\models\User',
            'identityClass' => 'common\modules\userProfile\models\UserExt',
            'enableAutoLogin' => true,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views/admin-lte3', //change layouts
                    '@dektrium/user/views' => '@frontend/modules/userProfile/views',
                    '@mdm/admin/views' => '@backend/modules/administrator/views'
                ],
            ],
        ],
        /*'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-purple', //change loyout
                ],
            ],
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'eav-equ' => 'eavEqu',
                'eav-equ/<controller>/<action>' => 'eavEqu/<controller>/<action>',
                'eav-equ/<controller>' => 'eavEqu/<controller>/index',
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php'
                    ],
                ],
                'hd*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/modules/helpdesk/messages',
                    'fileMap' => [
                        'hd' => 'app.php',
                        'app/error' => 'error.php'
                    ]
                ],
                'eav*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/modules/eavEqu/messages',
                    'fileMap' => [
                        'eav' => 'app.php',
                        'app/error' => 'error.php'
                    ]
                ],
                'equ*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/modules/equipment/messages',
                    'fileMap' => [
                        'equ' => 'app.php',
                        'app/error' => 'error.php'
                    ]
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '',
                'encryption' => 'ssl',
            ],

            'viewPath' => '@common/mail',

            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
    'modules' => [
        'user' => [
            // following line will restrict access to admin page
            'as backend' => 'dektrium\user\filters\BackendFilter',
        ],
        'eavEqu' => [
            'class' => 'yarcode\eav\modules\backend\Module',
            'modelsNamespace' => 'frontend\modules\equipment\models\eav',
            'entityName' => 'equ_act',
            'viewPath' => '@backend/modules/eavEqu/views',
            'controllerMap' => [
                'attribute-type' => [
                    'class' => 'backend\modules\eavEqu\controllers\AttributeTypeController',
                ],
                'attribute-category' => [
                    'class' => 'backend\modules\eavEqu\controllers\AttributeCategoryController',
                ],
            ],
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'developer']
                    ]
                ]
            ],
        ],
        /*'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'dektrium\user\models\User',
                    'idField' => 'id',
                ],
            ],
            'menus' => [
                'assignment' => [
                    'label' => 'Users'
                ],
                'route' => null,
            ],

        ],
        'roles' => [
            'class' => 'mdm\admin\Module',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    'allow' => false,
                    //'roles' => 'admin'
                ]
            ],
        ]*/
    ],
    'params' => $params,
];
