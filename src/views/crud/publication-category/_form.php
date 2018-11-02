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
 * @var dmstr\modules\publication\models\crud\PublicationCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="publication-category-form">

    <?php $form = ActiveForm::begin([
            'id' => 'PublicationCategory',
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
            <!-- attribute content_widget_template_id -->
            <?php echo
            $form->field($model, 'content_widget_template_id')->widget(\kartik\select2\Select2::class, [
                'name' => 'class_name',
                'model' => $model,
                'attribute' => 'content_widget_template_id',
                'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\HrzgWidgetTemplate::find()->all(), 'id', 'name'),
                'options' => [
                    'placeholder' => Yii::t('publication', 'Type to autocomplete'),
                    'multiple' => false,
                    'disabled' => (isset($relAttributes) && isset($relAttributes['content_widget_template_id'])),
                ]
            ]); ?>

            <!-- attribute teaser_widget_template_id -->
            <?php echo
            $form->field($model, 'teaser_widget_template_id')->widget(\kartik\select2\Select2::class, [
                'name' => 'class_name',
                'model' => $model,
                'attribute' => 'teaser_widget_template_id',
                'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\HrzgWidgetTemplate::find()->all(), 'id', 'name'),
                'options' => [
                    'placeholder' => Yii::t('publication', 'Type to autocomplete'),
                    'multiple' => false,
                    'disabled' => (isset($relAttributes) && isset($relAttributes['teaser_widget_template_id'])),
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
