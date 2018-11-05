<?php

use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationItem $model
 */
$this->title = Yii::t('publication', 'Publication Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('publication', 'Edit');
?>
<div class="giiant-crud publication-item-update">

    <h2>
        <small>
            <?php echo Html::encode($model->title) ?>
        </small>
    </h2>

    <div class="crud-navigation">
        <?php echo Html::a(FA::icon(FA::_FILE_O) . ' ' . Yii::t('publication', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr/>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
