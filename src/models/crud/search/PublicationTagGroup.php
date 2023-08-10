<?php

namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationTagGroup as PublicationTagGroupModel;
use dmstr\modules\publication\models\crud\PublicationTagGroupTranslation as PublicationTagGroupTranslationModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * PublicationTag represents the model behind the search form about
 * `dmstr\modules\publication\models\crud\PublicationTag`.
 *
 * @method string getFallbackLanguage($forLanguage = null)
 *
 * @property string $ref_lang
 *
 *
 */
class PublicationTagGroup extends PublicationTagGroupModel
{

    /**
     *
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [[['name', 'ref_lang'], 'safe'],];
    }


    /**
     *
     * @inheritdoc
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $appLanguage = Yii::$app->language;

        $query = PublicationTagGroupModel::find();
        $query->alias('tg');
        $query->select(['tg.id', 'ref_lang', 'name' => new Expression('COALESCE(t.name, ft.name)')]);
        $query->leftJoin(['t' => PublicationTagGroupTranslationModel::tableName()], 'tg.id = t.tag_group_id AND t.language = :appLanguage', [':appLanguage' => $appLanguage]);
        $query->leftJoin(['ft' => PublicationTagGroupTranslationModel::tableName()], 'tg.id = ft.tag_group_id AND ft.language = :fallbackLanguage', [':fallbackLanguage' => $this->getFallbackLanguage(Yii::$app->language)]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['LIKE', 'ref_lang', $this->ref_lang]);
        $query->andFilterHaving(['LIKE', 'name', $this->name]);

        return $dataProvider;
    }


}
