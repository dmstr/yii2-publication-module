<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationTag as PublicationTagModel;
use dmstr\modules\publication\models\crud\PublicationTagTranslation as PublicationTagTranslationModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;
use yii\db\Expression;

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
            [['name', 'ref_lang','tag_group_id'], 'safe'],
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

        $appLanguage = Yii::$app->language;

        $query = PublicationTag::find();
        $query->alias('tg');
        $query->select(['tg.id', 'tag_group_id', 'ref_lang', 'name' => new Expression('COALESCE(t.name, ft.name)')]);
        $query->leftJoin(['t' => PublicationTagTranslationModel::tableName()], 'tg.id = t.tag_id AND t.language = :appLanguage', [':appLanguage' => $appLanguage]);
        $query->leftJoin(['ft' => PublicationTagTranslationModel::tableName()], 'tg.id = ft.tag_id AND ft.language = :fallbackLanguage', [':fallbackLanguage' => $this->getFallbackLanguage(Yii::$app->language)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterHaving(['LIKE', 'name', $this->name]);

        $query->andFilterWhere(['LIKE', 'ref_lang', $this->ref_lang]);
        $query->andFilterWhere(['tag_group_id' => $this->tag_group_id]);

        return $dataProvider;
    }


}
