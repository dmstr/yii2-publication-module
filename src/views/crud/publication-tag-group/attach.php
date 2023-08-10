<?php

use dmstr\modules\publication\models\crud\PublicationTag;
use kartik\select2\Select2;
use dmstr\modules\publication\models\crud\PublicationTagGroup;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\modules\publication\models\crud\PublicationTagGroup $model
 * @var PublicationTag[] $attachedTags
 * @var PublicationTag[] $otherTags
 */
$this->title = Yii::t('publication', 'Publication Tag Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publication Tag Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('publication', 'Attach');
?>
<div class="giiant-crud publication-item-attach">

    <h2>
        <small>
            <?php echo Html::encode($model->name) ?>
        </small>
    </h2>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <?php echo Html::a(FA::icon(FA::_FILE_O) . ' ' . Yii::t('publication', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            </div>
            <div class="col-xs-12 col-sm-8">
                <?php

                echo Html::beginForm(Url::to(['/' . $this->context->module->id . '/' . $this->context->id . '/' . $this->context->action->id]), 'get');
                echo Select2::widget([
                    'data' => ArrayHelper::map(PublicationTagGroup::find()->all(), 'id', 'label'),
                    'name' => 'id',
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'value' => $model->id,
                    'pluginEvents' => [
                        'change' => 'function() {$(this).parents("form").submit();}'
                    ]
                ]);
                echo Html::endForm();
                ?>
            </div>
        </div>
    </div>

    <hr/>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?=Yii::t('publication','Attached Tags')?></div>
                    <ul class="list-group" data-sortable="target-list">
                    <?php foreach ($attachedTags as $tag):?>
                        <li class="list-group-item" data-key="<?= $tag->id ?>"><?=$tag->label?></li>
                    <?php endforeach;?>
                </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><?=Yii::t('publication','All Tags')?></div>
                    <ul class="list-group" data-sortable="source-list">
                    <?php foreach ($otherTags as $tag):?>
                    <li class="list-group-item" data-key="<?= $tag->id ?>"><?=$tag->label?></li>
                    <?php endforeach;?>
                </ul>
                </div>
            </div>
        </div>
    </div>
    <span data-url="<?= Url::to(['/' . $this->context->module->id . '/' . $this->context->id . '/attach-tags']) ?>"></span>
    <span data-item-id="<?= $model->id ?>"></span>
</div>
