<?php
/**
 * /app/src/../runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

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
		'layout' => 'horizontal',
		'enableClientValidation' => true,
		'errorSummaryCssClass' => 'error-summary alert alert-danger',
		'fieldConfig' => [
			'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
			'horizontalCssClasses' => [
				'label' => 'col-sm-2',
				//'offset' => 'col-sm-offset-4',
				'wrapper' => 'col-sm-8',
				'error' => '',
				'hint' => '',
			],
		],
	]
);
?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>


<!-- attribute category_id -->
			<?php echo $form->field($model, 'category_id')->widget(\kartik\select2\Select2::classname(), [
		'name' => 'class_name',
		'model' => $model,
		'attribute' => 'category_id',
		'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\PublicationCategoryTranslation::find()->where(['language_code' => \Yii::$app->language])->all(), 'id', 'title'),
		'options' => [
			'placeholder' => Yii::t('cruds', 'Type to autocomplete'),
			'multiple' => false,
			'disabled' => !$model->isNewRecord,
		]
	]); ?>
			<?php \dmstr\modules\publication\assets\PublicationItemAssetBundle::register($this);?>

<!-- attribute content_widget_json -->
			<?php echo $form->field($model, 'content_widget_json')->widget(\beowulfenator\JsonEditor\JsonEditorWidget::class, [
		'id' => 'content_widget_jsonEditor',
		'schema' =>$model->content_widget_schema,
		'enableSelectize' => true,
		'clientOptions' => [
			'theme' => 'bootstrap3',
			'disable_collapse' => true,
			'disable_properties' => true,
            'ajax' => true
		],
	]) ?>

<!-- attribute teaser_widget_json -->
			<?php echo $form->field($model, 'teaser_widget_json')->widget(\beowulfenator\JsonEditor\JsonEditorWidget::class, [
		'id' => 'teaser_widget_jsonEditor',
		'schema' =>$model->teaser_widget_schema,
		'enableSelectize' => true,
		'clientOptions' => [
			'theme' => 'bootstrap3',
			'disable_collapse' => true,
			'disable_properties' => true,
            'ajax' => true
		],
	]) ?>

<!-- attribute status -->
			<?php
$model->status = dmstr\modules\publication\models\crud\PublicationItem::STATUS_PUBLISHED;
?>
			<?php echo $form->field($model, 'status')->widget(\kartik\select2\Select2::class, [
		'data' => [$model::STATUS_PUBLISHED => \Yii::t('crud', 'Published'), $model::STATUS_DRAFT => \Yii::t('crud', 'Draft')] ]); ?>

<!-- attribute title -->
			<?php echo $form->field($model, 'title'); ?>

<!-- attribute release_date -->
			<?php echo $form->field($model, 'release_date')->widget(zhuravljov\yii\widgets\DateTimePicker::class, ['clientOptions' => ['autoclose' => true]]) ?>

<!-- attribute end_date -->
			<?php echo $form->field($model, 'end_date')->widget(zhuravljov\yii\widgets\DateTimePicker::class, ['clientOptions' => ['autoclose' => true]]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
Tabs::widget(
	[
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => Yii::t('models', 'PublicationItem'),
				'content' => $this->blocks['main'],
				'active'  => true,
			],
		]
	]
);
?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
	'<span class="glyphicon glyphicon-check"></span> ' .
	($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')),
	[
		'id' => 'save-' . $model->formName(),
		'class' => 'btn btn-success'
	]
);
?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
