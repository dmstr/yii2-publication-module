<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication;


use schmunk42\giiant\commands\BatchController;
use schmunk42\giiant\generators\crud\providers\core\CallbackProvider;
use schmunk42\giiant\generators\crud\providers\core\OptsProvider;
use schmunk42\giiant\generators\crud\providers\core\RelationProvider;

\Yii::$container->set(
    CallbackProvider::class,
    [
        'columnFormats' => [

        ],
        'attributeFormats' => [

        ],
        'activeFields' => [
            '_json' => function ($attribute) {
                $schemaProperty = str_replace('_json', '_schema', $attribute);
                return <<<PHP
\$form->field(\$model,'{$attribute}')->widget(\beowulfenator\JsonEditor\JsonEditorWidget::class,[
    'id' => 'editor',
    'schema' =>\$model->{$schemaProperty},
    'enableSelectize' => true,
    'clientOptions' => [
        'theme' => 'bootstrap3',
        'disable_collapse' => true,
        'disable_properties' => true,
    ],
])
PHP;
            },
            '_date' => function ($attribute) {
                return <<<PHP
\$form->field(\$model,'{$attribute}')->widget(zhuravljov\yii\widgets\DateTimePicker::class)
PHP;
            }
        ],
        'prependActiveFields' => [
            'status' => function ($attribute, $model) {
                return <<<PHP
<?php
\$model->{$attribute} = {$model::className()}::STATUS_PUBLISHED;
?>
PHP;
            }
        ],
        'appendActiveFields' => [
            'publication_category_id' => function () {
                return <<<PHP
<?php \dmstr\modules\publication\assets\PublicationItemAssetBundle::register(\$this);?>
PHP;
            }
        ],
    ]
);

\Yii::$container->set(
    OptsProvider::class,
    [
        'columnNames' => [
            'status' => 'radio'
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
        'publication-crud:batch' => [
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
            'useTablePrefix' => true,
            'crudProviders' => [
                CallbackProvider::class,
                OptsProvider::class,
                RelationProvider::class,
            ],
            'tablePrefix' => 'dmstr_',
            'tables' => [
                'dmstr_publication_category',
                'dmstr_publication_item'
            ],
            'tableNameMap' => [
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_category' => 'PublicationCategory',
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_item' => 'PublicationItem'
            ]
        ]
    ]
];