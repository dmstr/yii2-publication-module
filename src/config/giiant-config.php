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
use schmunk42\giiant\generators\crud\callbacks\base\Callback;
use schmunk42\giiant\generators\crud\providers\core\CallbackProvider;
use schmunk42\giiant\generators\crud\providers\core\OptsProvider;
use schmunk42\giiant\generators\crud\providers\core\RelationProvider;

$formats = [
    '^id|created_at|updated_at' => Callback::false(),
    '_date' => function ($attribute) {
        return <<<PHP
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => '{$attribute}',
    'value' => function (\$model) {
        return \Yii::\$app->formatter->asDateTime(\$model->{$attribute});
    },
    'format' => 'raw',
]
PHP;
    },
    'status' => function ($attribute) {
        return <<<PHP
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => '{$attribute}',
    'value' => function (\$model) {
        return '<div class="label label-' . (\$model->{$attribute} === 'published' ? 'success' : 'warning') . '">' . ucfirst(\$model->{$attribute}) . '</div>';
    },
    'format' => 'raw',
]
PHP;
    },
    'title' => function ($attribute) {
        return <<<PHP
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => '{$attribute}',
    'value' => function (\$model) {
        return \$model->{$attribute};
    },
    'format' => 'raw',
]
PHP;
    }
];

\Yii::$container->set(
    CallbackProvider::class,
    [
        'columnFormats' => $formats,
        'attributeFormats' => $formats,
        'activeFields' => [
            '_json' => function ($attribute) {
                $schemaProperty = str_replace('_json', '_schema', $attribute);
                return <<<PHP
\$form->field(\$model,'{$attribute}')->widget(\beowulfenator\JsonEditor\JsonEditorWidget::class,[
    'id' => '{$attribute}Editor',
    'schema' =>\$model->{$schemaProperty},
    'enableSelectize' => true,
    'clientOptions' => [
        'theme' => 'bootstrap3',
        'disable_collapse' => true,
        'disable_properties' => true,
        'ajax' => true
    ],
])
PHP;
            },
            '_date' => function ($attribute) {
                return <<<PHP
\$form->field(\$model,'{$attribute}')->widget(zhuravljov\yii\widgets\DateTimePicker::class,['clientOptions' => ['autoclose' => true]])
PHP;
            },
            'category_id' => function ($attribute) {
                return <<<PHP
\$form->field(\$model, '{$attribute}')->widget(\kartik\select2\Select2::classname(), [
		'name' => 'class_name',
		'model' => \$model,
		'attribute' => '{$attribute}',
		'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\PublicationCategoryTranslation::find()->where(['language_code' => \Yii::\$app->language])->all(), 'id', 'title'),
		'options' => [
			'placeholder' => Yii::t('cruds', 'Type to autocomplete'),
			'multiple' => false,
			'disabled' => !\$model->isNewRecord,
		]
	]);
PHP;
            },
            'title' => function ($attribute) {
                return <<<PHP
\$form->field(\$model, '{$attribute}');
PHP;
            },
            'status' => function ($attribute) {
                return <<<PHP
\$form->field(\$model, '{$attribute}')->widget(\kartik\select2\Select2::class, [
'data' => [\$model::STATUS_PUBLISHED => \Yii::t('crud','Published'),\$model::STATUS_DRAFT => \Yii::t('crud','Draft')] ]);
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
            'category_id' => function () {
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
            'crudAccessFilter' => false,
            'crudProviders' => [
                \schmunk42\giiant\generators\crud\providers\core\OptsProvider::class
            ],
            'crudBaseTraits' => implode(',', [
                '\\'.\dmstr\web\traits\AccessBehaviorTrait::class
            ]),
            'useTranslatableBehavior' => true,
            'languageCodeColumn' => 'language_code',
            'crudProviders' => [
                CallbackProvider::class,
                OptsProvider::class,
                RelationProvider::class,
            ],
            'tablePrefix' => 'dmstr_',
            'tables' => [
                'dmstr_publication_category',
                'dmstr_publication_category_translation',
                'dmstr_publication_item',
                'dmstr_publication_item_translation',
                'dmstr_publication_item_meta',
            ],
            'tableNameMap' => [
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_category' => 'PublicationCategory',
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_category_translation' => 'PublicationCategoryTranslation',
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_item' => 'PublicationItem',
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_item_translation' => 'PublicationItemTranslation',
                getenv('DATABASE_TABLE_PREFIX') . 'dmstr_publication_item_meta' => 'PublicationItemMeta'
            ]
        ]
    ]
];