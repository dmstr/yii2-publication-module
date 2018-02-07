<?php
/**
 * /app/src/../runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var dmstr\modules\publication\models\crud\search\PublicationItem $searchModel
 */
$this->title = Yii::t('models', 'Publication Items');
$this->params['breadcrumbs'][] = $this->title;


/**
 * create action column template depending acces rights
 */
$actionColumnTemplates = [];

if (\Yii::$app->user->can('controllers_publication-item_view', ['route' => true])) {
	$actionColumnTemplates[] = '{view}';
}

if (\Yii::$app->user->can('controllers_publication-item_update', ['route' => true])) {
	$actionColumnTemplates[] = '{update}';
}

if (\Yii::$app->user->can('controllers_publication-item_delete', ['route' => true])) {
	$actionColumnTemplates[] = '{delete}';
}
if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
	$actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']);
	$actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud publication-item-index">

    <?php
//             echo $this->render('_search', ['model' =>$searchModel]);
?>


    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1>
        <?php echo Yii::t('models', 'Publication Items') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
<?php
if (\Yii::$app->user->can('controllers_publication-item_create', ['route' => true])) {
?>
        <div class="pull-left">
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
<?php
}
?>
        <div class="pull-right">


            <?php echo
\yii\bootstrap\ButtonDropdown::widget(
	[
		'id' => 'giiant-relations',
		'encodeLabel' => false,
		'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('cruds', 'Relations'),
		'dropdown' => [
			'options' => [
				'class' => 'dropdown-menu-right'
			],
			'encodeLabels' => false,
			'items' => [
				[
					'url' => ['/publication/crud/publication-item-translation/index'],
					'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Publication Item Translation'),
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

    <hr />

    <div class="table-responsive">
        <?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'pager' => [
			'class' => yii\widgets\LinkPager::className(),
			'firstPageLabel' => Yii::t('cruds', 'First'),
			'lastPageLabel' => Yii::t('cruds', 'Last'),
		],
		'filterModel' => $searchModel,
		'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
		'headerRowOptions' => ['class'=>'x'],
		'columns' => [
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => $actionColumnTemplateString,
				'buttons' => [
					'view' => function ($url, $model, $key) {
						$options = [
							'title' => Yii::t('cruds', 'View'),
							'aria-label' => Yii::t('cruds', 'View'),
							'data-pjax' => '0',
						];
						return Html::a('<span class="glyphicon glyphicon-file"></span>', $url, $options);
					}


				],
				'urlCreator' => function($action, $model, $key, $index) {
					// using the column name as key, not mapping to 'id' like the standard generator
					$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
					$params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
					return Url::toRoute($params);
				},
				'contentOptions' => ['nowrap'=>'nowrap']
			],
			[
				'class' => yii\grid\DataColumn::className(),
				'attribute' => 'status',
				'value' => function ($model) {
					return '<div class="label label-' . ($model->status === 'published' ? 'success' : 'warning') . '">' . ucfirst($model->status) . '</div>';
				},
				'format' => 'raw',
			],
			[
				'class' => yii\grid\DataColumn::className(),
				'attribute' => 'release_date',
				'value' => function ($model) {
					return \Yii::$app->formatter->asDateTime($model->release_date);
				},
				'format' => 'raw',
			],
			[
				'class' => yii\grid\DataColumn::className(),
				'attribute' => 'end_date',
				'value' => function ($model) {
					return \Yii::$app->formatter->asDateTime($model->end_date);
				},
				'format' => 'raw',
			],
		],
	]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>
