<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationItemTranslation as PublicationItemTranslationModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationItemTranslation represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationItemTranslation`.
 */
class PublicationItemTranslation extends PublicationItemTranslationModel
{

    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content_widget_json', 'teaser_widget_json', 'language'], 'safe'],
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
        $query = PublicationItemTranslationModel::find();

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
            'item_id' => $this->item_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content_widget_json', $this->content_widget_json])
            ->andFilterWhere(['like', 'teaser_widget_json', $this->teaser_widget_json])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }


}
