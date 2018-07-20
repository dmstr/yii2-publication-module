<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\publication\models\crud\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dmstr\modules\publication\models\crud\PublicationCategoryTranslation as PublicationCategoryTranslationModel;

/**
 * PublicationCategoryTranslation represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationCategoryTranslation`.
 */
class PublicationCategoryTranslation extends PublicationCategoryTranslationModel
{

	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function rules() {
		return [
			[['id', 'category_id', 'created_at', 'updated_at'], 'integer'],
			[['language', 'title'], 'safe'],
		];
	}


	/**
	 *
	 * @inheritdoc
	 * @return unknown
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 *
	 * @param array   $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = PublicationCategoryTranslationModel::find();

		$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
				'id' => $this->id,
				'category_id' => $this->category_id,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
			]);

		$query->andFilterWhere(['like', 'language', $this->language])
		->andFilterWhere(['like', 'title', $this->title]);

		return $dataProvider;
	}


}
