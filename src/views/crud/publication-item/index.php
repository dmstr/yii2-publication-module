<?php

use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ButtonDropdown;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var dmstr\modules\publication\models\crud\search\PublicationItem $searchModel
 */
$this->title = Yii::t('publication', 'Publication Items');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="publication-item-index">

        <?php Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a']) ?>

        <h1>
            <?php echo Yii::t('publication', 'Publication Items') ?>
            <small>
                <?= Yii::t('publication', 'List') ?>
            </small>
        </h1>
        <div class="clearfix crud-navigation">
            <div class="pull-left">
                <?php echo Html::a(FA::icon(FA::_PLUS) . ' ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>

            <div class="pull-right">


                <?php echo
                ButtonDropdown::widget(
                    [
                        'id' => 'giiant-relations',
                        'encodeLabel' => false,
                        'label' => FA::icon(FA::_PAPERCLIP) . ' ' . Yii::t('cruds', 'Relations'),
                        'dropdown' => [
                            'options' => [
                                'class' => 'dropdown-menu-right'
                            ],
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'url' => ['/publication/crud/publication-category/index'],
                                    'label' => FA::icon(FA::_CHEVRON_LEFT) . ' ' . Yii::t('publication', 'Category'),
                                ],
                                [
                                    'url' => ['/publication/crud/publication-tag/index'],
                                    'label' => FA::icon(FA::_RANDOM) . ' ' . Yii::t('publication', 'Tag'),
                                ],
                            ]
                        ],
                        'options' => [
                            'class' => 'btn-default'
                        ]
                    ]
                );
                ?>
            </div>
        </div>

        <hr/>

        <div class="table-responsive">
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => LinkPager::class,
                    'firstPageLabel' => FA::icon(FA::_CHEVRON_RIGHT),
                    'lastPageLabel' => FA::icon(FA::_CHEVRON_LEFT),
                ],
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'rowOptions' => function (PublicationItem $model) {
                    if ($model->hasMethod('getTranslations')) {
                        return ['class' => $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() === null ? 'warning' : ''];
                    }
                    return [];
                },
                'columns' => [
                    [
                        'class' => \dmstr\modules\publication\widgets\ActiveStatusColumn::class,
                        'attribute' => 'status',
                        'activeValue' => PublicationItem::STATUS_PUBLISHED,
                        'endpoint' => ['/publication/crud/change-item-status']
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'title',
                        'label' => '',
                        'value' => function ($model) {
                            return $model->title;
                        },
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'release_date',
                        'label' => '',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'release_date',
                            [
                                2 => Yii::t('publication', 'Latest first'),
                                1 => Yii::t('publication', 'Oldest first')
                            ],
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('publication', 'Release Date')
                            ]
                        ),
                        'value' => function ($model) {
                            return \Yii::$app->formatter->asDatetime($model->release_date);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'category_id',
                        'label' => '',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'category_id',
                            [
                                1 => Yii::t('publication', 'A-Z'),
                                2 => Yii::t('publication', 'Z-A')
                            ],
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('publication', 'Category')
                            ]
                        ),
                        'value' => function ($model) {
                            return Html::a($model->category->label, ['/publication/crud/publication-category/view', 'id' => $model->category->id,], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'id',
                        'label' => '',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'id',
                            [
                                1 => Yii::t('publication', '0-9'),
                                2 => Yii::t('publication', '9-0')
                            ],
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('publication', 'ID')
                            ]
                        ),
                        'value' => function ($model) {
                            return $model->id;
                        },
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'ref_lang',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                $options = [
                                    'title' => Yii::t('cruds', 'View'),
                                    'aria-label' => Yii::t('cruds', 'View'),
                                    'data-pjax' => '0',
                                    'class' => 'btn-primary'
                                ];
                                return Html::a(FA::icon(FA::_EYE), $url, $options);
                            },
                            'update' => function ($url, PublicationItem $model) {
                                $options = [
                                    'title' => Yii::t('cruds', 'Update'),
                                    'aria-label' => Yii::t('cruds', 'Update'),
                                    'data-pjax' => '0',
                                    'class' => $model->hasMethod('getTranslations') ? $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() !== null ? 'btn-success' : 'btn-warning' : ''
                                ];
                                return Html::a(FA::icon(FA::_PENCIL), $url, $options);
                            },
                            'delete' => function ($url, PublicationItem $model) {
                                $options = [
                                    'title' => Yii::t('publication', 'Delete'),
                                    'aria-label' => Yii::t('publication', 'Delete'),
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                    'class' => 'btn-danger'
                                ];
                                if (PublicationHelper::checkModelAccess($model)) {
                                    $options['data-confirm'] = Yii::t('publication', 'Are you sure to delete this publication item?');
                                    return Html::a(FA::icon(FA::_TRASH_O), ['delete-base-model', 'id' => $model->id], $options);
                                }
                                if ($model->hasMethod('getTranslations') && $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() !== null) {
                                    $options['data-confirm'] = Yii::t('publication', 'Are you sure to delete this publication item translation?');
                                    return Html::a(FA::icon(FA::_TRASH_O), $url, $options);
                                }
                                return '';
                            }
                        ],
                        'urlCreator' => function ($action, PublicationItem $model, $key) {
                            $params = is_array($key) ? $key : [$model::primaryKey()[0] => (string)$key];
                            $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                            return Url::toRoute($params);
                        },
                        'contentOptions' => ['nowrap' => 'nowrap']
                    ],
                ],
            ]); ?>
        </div>

    </div>


<?php Pjax::end() ?>