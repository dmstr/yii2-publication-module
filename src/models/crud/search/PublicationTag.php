<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationTag as PublicationTagModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PublicationTag represents the model behind the search form about `dmstr\modules\publication\models\crud\PublicationTag`.
 *
 *
 */
class PublicationTag extends PublicationTagModel
{

    /**
     *
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'ref_lang'], 'safe'],
        ];
    }


    /**
     *
     * @inheritdoc
     * @return array
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
        $query->leftJoin(
            PublicationTagTranslation::tableName(),
            PublicationTagModel::tableName() . '.id = ' . PublicationTagTranslation::tableName() . '.tag_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere(['OR',
                [PublicationTagModel::tableName() . '.ref_lang' => !empty($this->ref_lang) ? $this->ref_lang : \Yii::$app->language],
                isset(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language]) ? [PublicationTagModel::tableName() . '.ref_lang' => !empty($this->ref_lang) ? $this->ref_lang : \Yii::$app->params['fallbackLanguages'][\Yii::$app->language]] : '']
        );

        $query->andFilterWhere(['LIKE', PublicationTagTranslation::tableName() . '.name', $this->name]);

        $query->andFilterWhere(['LIKE', 'ref_lang', $this->ref_lang]);


        return $dataProvider;
    }


}
