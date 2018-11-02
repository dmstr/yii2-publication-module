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

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationItem $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('publication', 'Publication Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('publication', 'View');
?>
<div class="giiant-crud publication-item-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo Yii::t('publication', 'Publication Item') ?>
        <small>
            <?php echo Html::encode($model->id) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(
                FA::icon(FA::_PENCIL) . ' ' . Yii::t('publication', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>


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

    <?php $this->beginBlock('dmstr\modules\publication\models\crud\PublicationItem'); ?>


    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'format' => 'html',
                'attribute' => 'category_id',
                'value' => ($model->category ?
                    Html::a(FA::icon(FA::_LIST), ['/publication/crud/publication-category/index']) . ' ' .
                    Html::a(FA::icon(FA::_CHEVRON_RIGHT) . ' ' . $model->category->label, ['/publication/crud/publication-category/view', 'id' => $model->category->id,]) . ' ' .
                    Html::a(FA::icon(FA::_PAPERCLIP), ['create', 'PublicationItem' => ['category_id' => $model->category_id]])
                    :
                    '<span class="label label-warning">?</span>'),
            ],
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'ref_lang',
            ],
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
                    return \Yii::$app->formatter->asDateTime($model->release_date);
                },
                'format' => 'raw',
            ],
            [
                'class' => yii\grid\DataColumn::class,
                'attribute' => 'end_date',
                'value' => function ($model) {
                    return \Yii::$app->formatter->asDateTime($model->end_date);
                },
                'format' => 'raw',
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







    <?php echo Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . Html::encode($model->id) . '</b>',
                    'content' => $this->blocks['dmstr\modules\publication\models\crud\PublicationItem'],
                    'active' => true,
                ]
            ]
        ]
    );
    ?>
</div>
