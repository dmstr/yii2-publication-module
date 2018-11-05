<?php
/**
 * /app/src/../runtime/giiant/d4b4964a63cc95065fa0ae19074007ee
 *
 * @package default
 */


use dmstr\bootstrap\Tabs;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\modules\publication\components\PublicationHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationTag $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('publication', 'Publication Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Tags'), 'url' => ['index']];
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

    <h1>
        <?php echo Yii::t('publication', 'Publication Tag') ?>
        <small>
            <?php echo Html::encode($model->name) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(FA::icon(FA::_PENCIL) . ' ' . Yii::t('publication', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>

            <?php echo Html::a(
                FA::icon(FA::_COPY) . ' ' . Yii::t('publication', 'Copy'),
                ['create', 'id' => $model->id, 'PublicationTag' => $copyParams],
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

    <?php $this->beginBlock('dmstr\modules\publication\models\crud\PublicationTag'); ?>


    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'name',
            ],
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'ref_lang',
            ]
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
            <?php echo Html::a(
                FA::icon(FA::_LIST) . ' ' . Yii::t('publication', 'List All') . ' ' . Yii::t('publication','Publication Items'),
                ['/publication/crud/publication-item/index'],
                ['class' => 'btn text-muted btn-xs']
            ) ?>
            <?php echo Html::a(
                FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New') . ' ' . Yii::t('publication','Publication Item'),
                ['/publication/crud/publication-item/create'],
                ['class' => 'btn btn-success btn-xs']
            ); ?>
        </div>
    </div>
    <?php Pjax::begin(['id' => 'pjax-PublicationItems', 'enableReplaceState' => false, 'linkSelector' => '#pjax-PublicationItems ul.pagination a, th a']) ?>
    <?php
    $tagModel = $model;
    echo
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
                'firstPageLabel' => Yii::t('publication', 'First'),
                'lastPageLabel' => Yii::t('publication', 'Last')
            ],
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
                                'title' => Yii::t('cruds', 'View'),
                                'aria-label' => Yii::t('cruds', 'View'),
                                'data-pjax' => '0',
                                'class' => 'btn-primary'
                            ];
                            return Html::a(FA::icon(FA::_EYE), $url, $options);
                        },
                        'update' => function ($url, $model) {
                            $options = [
                                'title' => Yii::t('cruds', 'Update'),
                                'aria-label' => Yii::t('cruds', 'Update'),
                                'data-pjax' => '0',
                                'class' => $model->hasMethod('getTranslations') ? $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() !== null ? 'btn-success' : 'btn-warning' : ''
                            ];
                            return Html::a(FA::icon(FA::_PENCIL), $url, $options);
                        },
                        'delete' => function ($url, $model) use ($tagModel) {
                            $options = [
                                'title' => Yii::t('publication', 'Delete'),
                                'aria-label' => Yii::t('publication', 'Delete'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn-danger'
                            ];
                            if ($model->ref_lang === Yii::$app->language) {
                                $options['data-confirm'] = Yii::t('publication', 'Are you sure to delete this attachment?');
                                return Html::a(FA::icon(FA::_UNLINK), ['delete-item-attachment', 'tagId' => $tagModel->id,'itemId' => $model->id], $options);
                            }
                            return '';
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key) {
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
                    'content' => $this->blocks['dmstr\modules\publication\models\crud\PublicationTag'],
                    'active' => true,
                ],
                [
                    'content' => $this->blocks['PublicationItems'],
                    'label' => '<small>' . Yii::t('publication','Publication Items') . ' <span class="badge badge-default">' . $model->getItems()->count() . '</span></small>',
                    'active' => false,
                ],
            ]
        ]
    );
    ?>
</div>
