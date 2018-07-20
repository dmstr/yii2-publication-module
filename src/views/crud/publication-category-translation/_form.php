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
 * @var dmstr\modules\publication\models\crud\PublicationCategoryTranslation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="publication-category-translation-form">

    <?php $form = ActiveForm::begin([
		'id' => 'PublicationCategoryTranslation',
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
		'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\PublicationCategoryTranslation::find()->where(['language' => \Yii::$app->language])->all(), 'id', 'title'),
		'options' => [
			'placeholder' => Yii::t('cruds', 'Type to autocomplete'),
			'multiple' => false,
			'disabled' => !$model->isNewRecord,
		]
	]); ?>
			<?php \dmstr\modules\publication\assets\PublicationItemAssetBundle::register($this);?>

<!-- attribute language -->
			<?php echo $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

<!-- attribute title -->
			<?php echo $form->field($model, 'title'); ?>
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
Tabs::widget(
	[
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => Yii::t('models', 'PublicationCategoryTranslation'),
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
