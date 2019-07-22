<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'search' => [
        'class' => 'fecshop\services\Search',
        /* example:
        'filterAttr' => [
            'color','size', # 在搜索页面侧栏的搜索过滤属性字段
        ],
        */
        'childService' => [
            'mongoSearch' => [
                'class'        => 'fecshop\services\search\MongoSearch',
                'searchIndexConfig'  => [
                    'name' => 10,
                    'description' => 5,
                ],
                //more : https://docs.mongodb.com/manual/reference/text-search-languages/#text-search-languages
                /* example:
                'searchLang'  => [
                    'en' => 'english',
                    'fr' => 'french',
                    'de' => 'german',
                    'es' => 'spanish',
                    'ru' => 'russian',
                    'pt' => 'portuguese',
                ],
                */
            ],
            'xunSearch'  => [
                'class'        => 'fecshop\services\search\XunSearch',
                /*

                'fuzzy' => true,  # 是否开启模糊查询
                'synonyms' => true, #是否开启同义词翻译
                'searchLang'    => [
                    'zh' => 'chinese',
                ],
                */
            ],
            'mysqlSearch'  => [
                'class'        => 'fecshop\services\search\MysqlSearch',
                /*
                'searchLang'  => [
                    'en' => 'english',
                    'fr'  => 'french',
                    'de' => 'german',
                    'es' => 'spanish',
                    'ru' => 'russian',
                    'pt' => 'portuguese',
                    'it'  =>  'italian',
                    'zh' => 'chinese',
                ],
                */
            ],
        ],
    ],
];
