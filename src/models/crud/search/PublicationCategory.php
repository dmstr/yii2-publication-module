<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationCategory as PublicationCategoryModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationCategory represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationCategory`.
 */
class PublicationCategory extends PublicationCategoryModel
{

    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function rules()
    {
        return [
            [['id', 'content_widget_template_id', 'teaser_widget_template_id'], 'integer'],
            ['ref_lang', 'safe']
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
        $query = PublicationCategoryModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'content_widget_template_id' => $this->content_widget_template_id,
            'teaser_widget_template_id' => $this->teaser_widget_template_id,
        ]);

        $query->andFilterWhere(['LIKE', 'ref_lang', $this->ref_lang]);


        return $dataProvider;
    }


}
