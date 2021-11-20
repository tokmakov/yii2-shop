<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'defaultRoute' => 'page',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main',
        ],
    ],
    'components' => [
        'request' => [
            // secret key in the following - this is required by cookie validation
            'cookieValidationKey' => 'k50nZdARB5-qNB9NNuOUDrNemC0gSCnd',
            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'defaultDuration' => 10
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'app/error'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'htmlLayout' => 'layouts/html',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // раздел каталога: 2, 3, 4 страница списка товаров
                'catalog/category/<id:\d+>/page/<page:\d+>' => 'catalog/category',
                // раздел каталога: первая страница списка товаров
                'catalog/category/<id:\d+>' => 'catalog/category',
                // бренд каталога: 2, 3, 4 страница списка товаров
                'catalog/brand/<id:\d+>/page/<page:\d+>' => 'catalog/brand',
                // бренд каталога: первая страница списка товаров
                'catalog/brand/<id:\d+>' => 'catalog/brand',
                // страница отдельного товара каталога
                'catalog/product/<id:\d+>' => 'catalog/product',
                // правило для 2, 3, 4 страницы результатов поиска
                'catalog/search/query/<query:.*?>/page/<page:\d+>' => 'catalog/search',
                // правило для первой страницы результатов поиска
                'catalog/search/query/<query:.*?>' => 'catalog/search',
                // правило для первой страницы с пустым запросом
                'catalog/search' => 'catalog/search',
                // страница сайта
                '/page/<slug:[-_0-9a-zA-Z]+>/' => 'page/view'
            ],
        ],
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['?'], // доступ для всех
            'root' => [
                'path' => 'images/pages', // директория внутри web
                'name' => 'Изображения'
            ],
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
