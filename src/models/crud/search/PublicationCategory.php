<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationCategory as PublicationCategoryModel;
use dmstr\modules\publication\models\crud\PublicationCategoryTranslation;
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
            [['ref_lang','title'], 'safe']
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
        $query->leftJoin(
            PublicationCategoryTranslation::tableName(),
            PublicationCategoryModel::tableName() . '.id = ' . PublicationCategoryTranslation::tableName() . '.category_id');
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

        $query->andWhere(['OR',
                [PublicationCategoryModel::tableName() . '.ref_lang' => !empty($this->ref_lang) ? $this->ref_lang : \Yii::$app->language],
                isset(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language]) ? [PublicationCategoryModel::tableName() . '.ref_lang' => !empty($this->ref_lang) ? $this->ref_lang : \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]] : '']
        );

        $query->andFilterWhere(['LIKE', 'ref_lang', $this->ref_lang]);
        $query->andFilterWhere(['LIKE', 'title', $this->title]);


        return $dataProvider;
    }


}
