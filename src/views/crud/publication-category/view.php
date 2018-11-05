<?php

use dmstr\bootstrap\Tabs;
use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use rmrevin\yii\fontawesome\FA;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationCategory $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('publication', 'Publication Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('publication', 'View');
?>
<div class="giiant-crud publication-category-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h2>
        <small>
            <?php echo Html::encode($model->label) ?>
        </small>
    </h2>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(FA::icon(FA::_PENCIL) . ' ' . Yii::t('publication', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>

            <?php echo Html::a(
                FA::icon(FA::_COPY) . ' ' . Yii::t('publication', 'Copy'),
                ['create', 'id' => $model->id, 'PublicationCategory' => $copyParams],
                ['class' => 'btn btn-success']) ?>

            <?php echo Html::a(
                FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New'),
                ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?php echo Html::a(FA::icon(FA::_LIST) . ' '
                . Yii::t('publication', 'Full list'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('dmstr\modules\publication\models\crud\PublicationCategory'); ?>


    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->title;
                },
                'format' => 'raw',
            ],
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'ref_lang',
            ],
            [
                'format' => 'html',
                'attribute' => 'content_widget_template_id',
                'value' => ($model->contentWidgetTemplate ?
                    Html::a(FA::icon(FA::_LIST), ['/widgets/crud/widget-template/index']) . ' ' .
                    Html::a(FA::icon(FA::_CHEVRON_RIGHT) . ' ' . $model->contentWidgetTemplate->name, ['/widgets/crud/widget-template/view', 'id' => $model->contentWidgetTemplate->id,])
                    : '<span class="label label-warning">?</span>'),

            ],

            [
                'format' => 'html',
                'attribute' => 'teaser_widget_template_id',
                'value' => ($model->teaserWidgetTemplate ?
                    Html::a(FA::icon(FA::_LIST), ['/widgets/crud/widget-template/index']) . ' ' .
                    Html::a(FA::icon(FA::_CHEVRON_RIGHT) . ' ' . $model->teaserWidgetTemplate->name, ['/widgets/crud/widget-template/view', 'id' => $model->teaserWidgetTemplate->id,])
                    : '<span class="label label-warning">?</span>'),
            ],
        ],
    ]); ?>


    <hr/>

    <?php echo Html::a(FA::icon(FA::_TRASH_O) . ' ' . Yii::t('publication', 'Delete'), ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => '' . Yii::t('publication', 'Are you sure to delete this item?') . '',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>


    <?php $this->beginBlock('PublicationItems'); ?>
    <div style='position: relative'>
        <div style='position:absolute; right: 0px; top: 0px;'>
            <?php if(Yii::$app->user->can('publication_crud_publication-item_index') || Yii::$app->user->can('publication_crud_publication-item')): ?>
            <?php echo Html::a(
                FA::icon(FA::_LIST) . ' ' . Yii::t('publication', 'List All') . ' Publication Items',
                ['/publication/crud/publication-item/index'],
                ['class' => 'btn text-muted btn-xs']
            ) ?>
            <?php echo Html::a(
                FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New') . ' Publication Item',
                ['/publication/crud/publication-item/create', 'PublicationItem' => ['category_id' => $model->id]],
                ['class' => 'btn btn-success btn-xs']
            ); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php Pjax::begin(['id' => 'pjax-PublicationItems', 'enableReplaceState' => false, 'linkSelector' => '#pjax-PublicationItems ul.pagination a, th a']) ?>
    <?php echo
        '<div class="table-responsive">'
        . \yii\grid\GridView::widget([
            'layout' => '{summary}{pager}<br/>{items}{pager}',
            'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getItems(),
                'pagination' => [
                    'pageSize' => 20,
                    'pageParam' => 'page-publication-items',
                ]
            ]),
            'pager' => [
                'class' => yii\widgets\LinkPager::class,
                'firstPageLabel' => FA::icon(FA::_CHEVRON_LEFT),
                'lastPageLabel' => FA::icon(FA::_CHEVRON_RIGHT),
            ],
            'rowOptions' => function (PublicationItem $model) {
                if ($model->hasMethod('getTranslations')) {
                    return ['class' => $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() === null ? 'warning' : ''];
                }
                return [];
            },
            'columns' => [
                [
                    'class' => yii\grid\DataColumn::class,
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return '<div class="label label-' . ($model->status === 'published' ? 'success' : 'warning') . '">' . ucfirst($model->status) . '</div>';
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => yii\grid\DataColumn::class,
                    'attribute' => 'title',
                    'value' => function ($model) {
                        return $model->title;
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => yii\grid\DataColumn::class,
                    'attribute' => 'release_date',
                    'value' => function ($model) {
                        return \Yii::$app->formatter->asDatetime($model->release_date);
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => yii\grid\DataColumn::class,
                    'attribute' => 'end_date',
                    'value' => function ($model) {
                        return \Yii::$app->formatter->asDatetime($model->end_date);
                    },
                    'format' => 'raw',
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
                            Yii::$app->controller->view->registerJs('$(function () {$(\'[data-toggle="tooltip"]\').tooltip()})');
                            return Html::tag('div',FA::icon(FA::_TRASH_O),['data-toggle' => 'tooltip', 'class' => 'btn btn-danger disabled','title' => Yii::t('publication','You are not allowed to delete this record.')]);
                        }
                    ],
                    'urlCreator' => function ($action, PublicationItem $model, $key) {
                        $params = is_array($key) ? $key : [$model::primaryKey()[0] => (string)$key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
            ]
        ])
        . '</div>'
    ?>
    <?php Pjax::end() ?>
    <?php $this->endBlock() ?>


    <?php echo Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . Html::encode($model->id) . '</b>',
                    'content' => $this->blocks['dmstr\modules\publication\models\crud\PublicationCategory'],
                    'active' => true,
                ],
                [
                    'content' => $this->blocks['PublicationItems'],
                    'label' => '<small>Publication Items <span class="badge badge-default">' . $model->getItems()->count() . '</span></small>',
                    'active' => false,
                ],
            ]
        ]
    );
    ?>
</div>
