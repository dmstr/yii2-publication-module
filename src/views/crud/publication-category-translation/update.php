<?php
/**
 * /app/src/../runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Publication Category Translation'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Edit');
?>
<div class="giiant-crud publication-category-translation-update">

    <h1>
        <?php echo Yii::t('models', 'Publication Category Translation') ?>
        <small>
                        <?php echo Html::encode($model->title) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('cruds', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
