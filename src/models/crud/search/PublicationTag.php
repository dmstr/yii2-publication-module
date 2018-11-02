<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationTag as PublicationTagModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationTag represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationTag`.
 */
class PublicationTag extends PublicationTagModel
{

    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
        $query = PublicationTagModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

//        $query->andFilterWhere(['LIKE', 'name', $this->name]);


        return $dataProvider;
    }


}
