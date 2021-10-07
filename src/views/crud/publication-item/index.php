<?php

use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\PublicationItem;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ButtonDropdown;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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
$this->registerJs('$(function () {$(\'[data-toggle="tooltip"]\').tooltip()})');

?>
    <div class="publication-item-index">

        <?php Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a']) ?>

        <div class="clearfix crud-navigation">
            <div class="pull-left">
                <?php echo Html::a(FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>

            <div class="pull-right">


                <?php echo
                ButtonDropdown::widget(
                    [
                        'id' => 'giiant-relations',
                        'encodeLabel' => false,
                        'label' => FA::icon(FA::_PAPERCLIP) . ' ' . Yii::t('publication', 'Relations'),
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
                    'firstPageLabel' => FA::icon(FA::_CHEVRON_LEFT),
                    'lastPageLabel' => FA::icon(FA::_CHEVRON_RIGHT),
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
                        'value' => function ($model) {
                            return Html::encode($model->title);
                        },
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'release_date',
                        'value' => function ($model) {
                            return \Yii::$app->formatter->asDatetime($model->release_date);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'category_id',
                        'value' => function ($model) {
                            return Html::a($model->category->label, ['/publication/crud/publication-category/view', 'id' => $model->category->id,], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                        'filter' => ArrayHelper::map(PublicationCategory::find()->all(), 'id', 'label'),
                    ],
                    [
                        'class' => DataColumn::class,
                        'attribute' => 'id',
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
                        'template' => '{tags}',
                        'buttons' => [
                            'tags' => function ($url, PublicationItem $model, $key) {

                                $tags = [];

                                foreach (ArrayHelper::map($model->tags,'id','label') as $id => $label) {
                                    $tags[] =  Html::a(Html::encode($label),['/publication/crud/publication-tag/view','id' => $id],['class' => 'label label-default']);
                                }

                                // attach tags is only allowed for "own" items
                                // see: \dmstr\modules\publication\controllers\crud\PublicationItemController::actionAttach()
                                if ($model->ref_lang === Yii::$app->language) {
                                    $tags[] =  Html::a(FA::icon(FA::_PLUS),['/publication/crud/publication-item/attach','id' => $model->id],['class' => 'label label-success']);
                                }

                                return implode(' ', $tags);

                            }
                        ],
                        'contentOptions' => ['nowrap' => 'nowrap']
                    ],
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                $options = [
                                    'title' => Yii::t('publication', 'View'),
                                    'aria-label' => Yii::t('publication', 'View'),
                                    'data-pjax' => '0',
                                    'class' => 'btn-primary'
                                ];
                                return Html::a(FA::icon(FA::_EYE), $url, $options);
                            },
                            'update' => function ($url, PublicationItem $model) {
                                $options = [
                                    'title' => Yii::t('publication', 'Update'),
                                    'aria-label' => Yii::t('publication', 'Update'),
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
                                return Html::tag('div', FA::icon(FA::_TRASH_O), ['data-toggle' => 'tooltip', 'class' => 'btn btn-danger disabled', 'title' => Yii::t('publication', 'You are not allowed to delete this record.')]);
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