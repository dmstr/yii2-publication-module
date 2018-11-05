<?php

use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationCategory $model
 */
$this->title = Yii::t('publication', 'Publication Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud publication-category-create">

    <h2>
        <?php echo Yii::t('publication', 'Publication Category') ?>
        <small>
            <?php echo Html::encode($model->label) ?>
        </small>
    </h2>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a(
                Yii::t('publication', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr/>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
