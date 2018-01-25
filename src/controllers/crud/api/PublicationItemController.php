<?php
/**
 * /app/src/../runtime/giiant/f197ab8e55d1e29a2dea883e84983544
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud\api;

/**
 * This is the class for REST controller "PublicationItemController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class PublicationItemController extends \yii\rest\ActiveController
{
	public $modelClass = 'dmstr\modules\publication\models\crud\PublicationItem';

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function behaviors() {
		return ArrayHelper::merge(
			parent::behaviors(),
			[
				'access' => [
					'class' => AccessControl::className(),
					'rules' => [
						[
							'allow' => true,
							'matchCallback' => function ($rule, $action) {return \Yii::$app->user->can($this->module->id . '_' . $this->id . '_' . $action->id, ['route' => true]);},
						]
					]
				]
			]
		);
	}


}
