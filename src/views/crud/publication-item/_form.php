<?php
/**
 * /app/src/../runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationItem $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="publication-item-form">

    <?php $form = ActiveForm::begin([
            'id' => 'PublicationItem',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    ?>

    <div class="form-group pull-left">
        <?= Html::submitButton(
            FA::icon(FA::_SAVE) . ' ' .
            ($model->isNewRecord ? Yii::t('publication', 'Create') : Yii::t('publication', 'Save')),
            [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>
        <?= $this->context->action->id === 'update' ? Html::a(FA::icon(FA::_EYE) . ' ' . Yii::t('publication', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) : '' ?>
    </div>
    <?= Html::a(FA::icon(FA::_LIST) . ' ' . Yii::t('publication', 'Full list'), ['index'], ['class' => 'btn btn-default pull-right']) ?>
    <span class="clearfix"></span>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= Yii::t('publication', 'General') ?>
            </h3>
        </div>
        <div class="panel-body">

            <!-- attribute category_id -->
            <?php echo $form->field($model, 'category_id')->widget(\kartik\select2\Select2::class, [
                'name' => 'class_name',
                'model' => $model,
                'attribute' => 'category_id',
                'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\PublicationCategory::find()->all(), 'id', 'title'),
                'options' => [
                    'placeholder' => Yii::t('cruds', 'Type to autocomplete'),
                    'multiple' => false,
                    'disabled' => !$model->isNewRecord,
                ]
            ]); ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('publication', 'Translation') ?></h3>
        </div>
        <div class="panel-body">
            <!-- attribute title -->
            <?php echo $form->field($model, 'title'); ?>

            <?php \dmstr\modules\publication\assets\PublicationItemAssetBundle::register($this); ?>

            <!-- attribute content_widget_json -->
            <?php echo $form->field($model, 'content_widget_json')->widget(dmstr\jsoneditor\JsonEditorWidget::class, [
                'id' => 'content_widget_jsonEditor',
                'schema' => $model->content_widget_schema,
                'clientOptions' => [
                    'theme' => 'bootstrap3',
                    'disable_collapse' => true,
                    'disable_properties' => true,
                    'keep_oneof_values' => false,
                    'ajax' => true
                ],
            ]) ?>

            <!-- attribute teaser_widget_json -->
            <?php echo $form->field($model, 'teaser_widget_json')->widget(dmstr\jsoneditor\JsonEditorWidget::class, [
                'id' => 'teaser_widget_jsonEditor',
                'schema' => $model->teaser_widget_schema,
                'clientOptions' => [
                    'theme' => 'bootstrap3',
                    'disable_collapse' => true,
                    'disable_properties' => true,
                    'keep_oneof_values' => false,
                    'ajax' => true
                ],
            ]) ?>

            <!-- attribute release_date -->
            <?php echo $form->field($model, 'release_date')->widget(zhuravljov\yii\widgets\DateTimePicker::class, ['clientOptions' => ['autoclose' => true]]) ?>

            <!-- attribute end_date -->
            <?php echo $form->field($model, 'end_date')->widget(zhuravljov\yii\widgets\DateTimePicker::class, ['clientOptions' => ['autoclose' => true]]) ?>

        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('publication', 'Metadata') ?></h3>
        </div>
        <div class="panel-body">

            <!-- attribute status -->
            <?php
            $model->status = dmstr\modules\publication\models\crud\PublicationItem::STATUS_PUBLISHED;
            ?>
            <?php echo $form->field($model, 'status')->widget(\kartik\select2\Select2::class, [
                'data' => [$model::STATUS_PUBLISHED => \Yii::t('crud', 'Published'), $model::STATUS_DRAFT => \Yii::t('crud', 'Draft')]]); ?>

        </div>
    </div>
    <?php echo $form->errorSummary($model); ?>

    <?php echo Html::submitButton(
        FA::icon(FA::_SAVE) . ' ' .
        ($model->isNewRecord ? Yii::t('publication', 'Create') : Yii::t('publication', 'Save')),
        [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
        ]
    );
    ?>

    <?php ActiveForm::end(); ?>
</div>

