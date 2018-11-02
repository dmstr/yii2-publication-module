<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


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
            [['id', 'content_widget_template_id', 'teaser_widget_template_id', 'created_at', 'updated_at'], 'integer'],
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
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'content_widget_template_id' => $this->content_widget_template_id,
            'teaser_widget_template_id' => $this->teaser_widget_template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }


}
