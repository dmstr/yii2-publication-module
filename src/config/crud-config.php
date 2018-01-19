<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication;


use schmunk42\giiant\generators\crud\providers\core\CallbackProvider;
use schmunk42\giiant\generators\crud\providers\core\OptsProvider;
use schmunk42\giiant\generators\crud\providers\core\RelationProvider;
use schmunk42\giiant\commands\BatchController;


\Yii::$container->set(
    CallbackProvider::class,
    [
        'columnFormats' => [

        ],
        'attributeFormats' => [

        ],
        'activeFields' => [
            '_date' => function ($attribute) {

        return <<<PHP
$this->field(\$model, '{$attribute}')->widget(\zhuravljov\yii\widgets\DateTimePicker::class, []);
PHP;
            }
        ],
    ]
);

\Yii::$container->set(
    OptsProvider::class,
    [
        'columnNames' => [
            'status' => 'checkbox'
        ]
    ]
);

\Yii::$container->set(
    RelationProvider::class,
    [
        'inputWidget' => 'select2',
    ]
);


return [
    'controllerMap' => [
        'crud:batch' => [
            'class' => BatchController::class,
            'overwrite' => true,
            'interactive' => false,
            'modelNamespace' => __NAMESPACE__ . '\\models\\crud',
            'modelQueryNamespace' => __NAMESPACE__ . '\\models\\crud\\query',
            'crudControllerNamespace' => __NAMESPACE__ . '\\controllers\\crud',
            'crudSearchModelNamespace' => __NAMESPACE__ . '\\models\\crud\\search',
            'crudViewPath' => '@' . str_replace('\\', '/', __NAMESPACE__) . '/views/crud',
            'crudPathPrefix' => '/publication/crud/',
            'crudTidyOutput' => true,
            'crudAccessFilter' => true,
            'crudProviders' => [
                CallbackProvider::class,
                OptsProvider::class,
                RelationProvider::class,
            ],
            'tablePrefix' => 'dmstr_',
            'tables' => [
                'dmstr_publication_category',
                'dmstr_publication_item'
            ]
        ]
    ]
];