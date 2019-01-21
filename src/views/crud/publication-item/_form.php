<?php

use dmstr\modules\publication\models\crud\PublicationTag;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
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
                    'id' => 'categoryId',
                    'model' => $model,
                    'attribute' => 'category_id',
                    'data' => \yii\helpers\ArrayHelper::map(dmstr\modules\publication\models\crud\PublicationCategory::find()->all(), 'id', 'title'),
                    'options' => [
                        'placeholder' => Yii::t('publication', 'Type to autocomplete'),
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
                <span class="mez-info"><?= Yii::t('publication', 'Selected UTC release date time corresponds to CEST {offset} hours', ['offset' => Html::tag('span', null, ['id' => 'release-date-mez-offset'])]) ?></span>
                <br>
                <br>
                <!-- attribute end_date -->
                <?php echo $form->field($model, 'end_date')->widget(zhuravljov\yii\widgets\DateTimePicker::class, ['clientOptions' => ['autoclose' => true]]) ?>
                <span class="mez-info"><?= Yii::t('publication', 'Selected UTC end date time corresponds to CEST {offset} hours', ['offset' => Html::tag('span', null, ['id' => 'end-date-mez-offset'])]) ?></span>

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

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('publication', 'Additions') ?></h3>
            </div>
            <div class="panel-body">
                <?php if ($model->ref_lang === Yii::$app->language || $model->isNewRecord):


                    echo $form->field($model, 'tagIds')->widget(\kartik\select2\Select2::class, [
                        'name' => 'tagIds',
                        'attribute' => 'tagIds',
                        'model' => $model,
                        'data' => ArrayHelper::map(PublicationTag::find()->all(), 'id', 'label'),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                            'placeholder' => Yii::t('publication', 'Select tags'),
                        ]
                    ]);

                    if (Yii::$app->user->can('publication_crud_publication-tag_index') || Yii::$app->user->can('publication_crud_publication-tag')) {
                        echo Html::a(Yii::t('publication', 'Add New Tag'), ['/publication/crud/publication-tag/create'], ['class' => 'btn btn-xs btn-success pull-right', 'target' => '_blank']);
                        echo Html::tag('span', null, ['class' => 'clearfix']);
                    }

                    ?>
                <?php else: ?>
                    <p class="text-muted"><?= Yii::t('publication', 'Attached tags for this item. Click to see further informations.') ?></p>
                    <?php foreach ($model->tags as $tag) {
                        echo ' ' . Html::a($tag->label, ['/publication/crud/publication-tag/view', 'id' => $tag->id], ['class' => 'label label-primary', 'target' => '_blank']);
                    } ?>
                <?php endif; ?>
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
<?php
// TODO: this is just a positioning workaround
// see: https://github.com/dmstr/yii2-widgets2-module/blob/e3bddeb560fd37a023e8d4adccda7567d1510d74/src/views/crud/widget/_form.php#L221
$js = file_get_contents(Yii::getAlias('@hrzg/widget/assets/web/widgets-init.js'));
$this->registerJs($js);

