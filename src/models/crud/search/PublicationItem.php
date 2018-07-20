<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationItem as PublicationItemModel;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationItem represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationItem`.
 */
class PublicationItem extends PublicationItemModel
{

    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['release_date', 'end_date', 'title'], 'safe'],
        ];
    }


    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PublicationItemModel::find();
        $query->select([PublicationItemModel::tableName() . '.*', PublicationItemTranslation::tableName().'.title', PublicationItemTranslation::tableName().'.language']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin(
            PublicationItemTranslation::tableName(),
            PublicationItemModel::tableName() . '.id = ' . PublicationItemTranslation::tableName() . '.item_id');

        $query->andFilterWhere(['LIKE', PublicationItemTranslation::tableName() . '.title', $this->title]);

        $query->andFilterWhere([
            'id' => $this->id,
            'release_date' => $this->release_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andWhere([PublicationItemTranslation::tableName().'.language' => \Yii::$app->language]);

        return $dataProvider;
    }


}
