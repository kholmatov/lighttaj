<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'awssdk' => [
            'class' => 'fedemotta\awssdk\AwsSdk',
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'region' => 'us-east-1', //i.e.: 'us-east-1'
            'version' => 'latest', //i.e.: 'latest'
            'scheme' => 'http' //ssl check disable
        ]
    ],
];
