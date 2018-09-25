<?php

use dmstr\modules\publication\models\crud\PublicationItem;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ButtonDropdown;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
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
                List
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
                                    'label' => FA::icon(FA::_ARROW_LEFT) . ' ' . Yii::t('publication', 'Publication Category'),
                                ],
                                [
                                    'url' => ['/publication/crud/publication-item-meta/index'],
                                    'label' => FA::icon(FA::_ARROW_RIGHT) . ' ' . Yii::t('publication', 'Publication Item Meta'),
                                ],
                                [
                                    'url' => ['/publication/crud/publication-item-translation/index'],
                                    'label' => FA::icon(FA::_ARROW_RIGHT) . ' ' . Yii::t('publication', 'Publication Item Translation'),
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
                            return \Yii::$app->formatter->asDateTime($model->release_date);
                        },
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
                        'attribute' => 'status',
                        'label' => '',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            [
                                1 => Yii::t('publication', 'Draft first'),
                                2 => Yii::t('publication', 'Published first')
                            ],
                            [
                                'class' => 'form-control',
                                'prompt' => Yii::t('publication', 'Status')
                            ]
                        ),
                        'value' => function ($model) {
                            return Html::tag('span', FA::icon(FA::_CHECK, ['class' => 'text-success ' . ($model->status !== PublicationItem::STATUS_PUBLISHED ? 'hidden' : '')]) . FA::icon(FA::_TIMES, ['class' => 'text-danger ' . ($model->status !== PublicationItem::STATUS_DRAFT ? 'hidden' : '')]), ['class' => 'status-label-' . $model->id . ' status-label-' . $model->status]);
                        },
                        'format' => 'raw'
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
                        'label' => '',
                        'value' => function ($model) {
                            return Html::a(\Yii::t('publication', '{eye-icon} View frontend page', ['eye-icon' => FA::icon(FA::_EYE)]), ['/' . $this->context->module->id . '/default/detail', 'itemId' => $model->id,], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => DataColumn::class,
                        'label' => '',
                        'value' => function ($model) {
                            return Html::a(\Yii::t('publication', '{pencil-icon} Edit', ['pencil-icon' => FA::icon(FA::_PENCIL)]), ['/' . $this->context->module->id . '/' . $this->context->id . '/update', 'id' => $model->id,], ['data-pjax' => 0]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => DataColumn::class,
                        'label' => '',
                        'value' => function ($model) {
                            return Html::a(\Yii::t('publication', '{glyphicon glyphicon-trash} Delete', ['glyphicon glyphicon-trash' => FA::icon(FA::_TRASH)]), ['/' . $this->context->module->id . '/' . $this->context->id . '/delete', 'id' => $model->id,], ['data-pjax' => 0,'onclick' => 'if (confirm("Are you sure you want to delete this item?")) commentDelete(1); return false']);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        </div>

    </div>


<?php Pjax::end() ?>