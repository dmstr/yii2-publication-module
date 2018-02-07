<?php
/**
 * /app/src/../runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationCategoryTranslation $model
 */
$this->title = Yii::t('models', 'Publication Category Translation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Publication Category Translations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud publication-category-translation-create">

    <h1>
        <?php echo Yii::t('models', 'Publication Category Translation') ?>
        <small>
                        <?php echo $model->title ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
	Yii::t('cruds', 'Cancel'),
	\yii\helpers\Url::previous(),
	['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
