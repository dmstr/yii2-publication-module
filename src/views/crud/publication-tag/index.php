<?php
/**
 * /app/src/../runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationTag;
use rmrevin\yii\fontawesome\FA;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var dmstr\modules\publication\models\crud\search\PublicationTag $searchModel
 */

$this->title = Yii::t('publication', 'Publication Tags');
$this->params['breadcrumbs'][] = $this->title;

if (isset($actionColumnTemplates)) {
    $actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
    Yii::$app->view->params['pageButtons'] = Html::a(FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New'), ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">' . $actionColumnTemplateString . '</div>';
?>
<div class="giiant-crud publication-tag-index">

    <?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

    <h1>
        <?php echo Yii::t('publication', 'Publication Tags') ?>
        <small>
            <?= Yii::t('publication', 'List') ?>
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a(FA::icon(FA::_PLUS) . ' ' . Yii::t('publication', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">


            <?php echo
            \yii\bootstrap\ButtonDropdown::widget(
                [
                    'id' => 'giiant-relations',
                    'encodeLabel' => false,
                    'label' => FA::icon(FA::_PAPERCLIP) . ' ' . Yii::t('publication', 'Relations'),
                    'dropdown' => [
                        'options' => [
                            'class' => 'dropdown-menu-right'
                        ],
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'url' => ['/widgets/crud/widget-template/index'],
                                'label' => FA::icon(FA::_CHEVRON_LEFT) . ' ' . Yii::t('publication', 'TWIG Templates'),
                            ],
                            [
                                'url' => ['/publication/crud/publication-item/index'],
                                'label' => FA::icon(FA::_CHEVRON_RIGHT) . ' ' . Yii::t('publication', 'Publication Item'),
                            ],

                        ]
                    ],
                    'options' => [
                        'class' => 'btn-default'
                    ]
                ]
            );
            ?>
        </div>
    </div>

    <hr/>

    <div class="table-responsive">
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::class,
                'firstPageLabel' => Yii::t('publication', 'First'),
                'lastPageLabel' => Yii::t('publication', 'Last'),
            ],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [
                [
                    'class' => DataColumn::class,
                    'attribute' => 'name',
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url) {
                            $options = [
                                'title' => Yii::t('cruds', 'View'),
                                'aria-label' => Yii::t('cruds', 'View'),
                                'data-pjax' => '0',
                                'class' => 'btn-primary'
                            ];
                            return Html::a(FA::icon(FA::_EYE), $url, $options);
                        },
                        'update' => function ($url, PublicationTag $model) {
                            $options = [
                                'title' => Yii::t('cruds', 'Update'),
                                'aria-label' => Yii::t('cruds', 'Update'),
                                'data-pjax' => '0',
                                'class' => $model->hasMethod('getTranslations') ? $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() !== null ? 'btn-success' : 'btn-warning' : ''
                            ];
                            return Html::a(FA::icon(FA::_PENCIL), $url, $options);
                        },
                        'delete' => function ($url, PublicationTag $model) {
                            $options = [
                                'title' => Yii::t('publication', 'Delete'),
                                'aria-label' => Yii::t('publication', 'Delete'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn-danger'
                            ];
                            if (PublicationHelper::checkModelAccess($model)) {
                                $options['data-confirm'] = Yii::t('publication', 'Are you sure to delete this publication tag?');
                                return Html::a(FA::icon(FA::_TRASH_O), ['delete-base-model', 'id' => $model->id], $options);
                            }
                            if ($model->hasMethod('getTranslations') && $model->getTranslations()->andWhere(['language' => Yii::$app->language])->one() !== null) {
                                $options['data-confirm'] = Yii::t('publication', 'Are you sure to delete this publication tag translation?');
                                return Html::a(FA::icon(FA::_TRASH_O), $url, $options);
                            }
                            return '';
                        }
                    ],
                    'urlCreator' => function ($action, PublicationTag $model, $key) {
                        $params = is_array($key) ? $key : [$model::primaryKey()[0] => (string)$key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap']
                ],
            ],
        ]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>
