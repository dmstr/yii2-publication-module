<?php
/**
 * /app/src/../runtime/giiant/e0080b9d6ffa35acb85312bf99a557f2
 *
 * @package default
 */


namespace dmstr\modules\publication\models\crud\search;

use dmstr\modules\publication\models\crud\PublicationCategoryTranslation;
use dmstr\modules\publication\models\crud\PublicationItem as PublicationItemModel;
use dmstr\modules\publication\models\crud\PublicationItemMeta;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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
            [['id'], 'integer'],
            [['release_date', 'title', 'status', 'category_id'], 'safe'],
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
        $query = PublicationItemModel::find();
        $query->select([PublicationItemModel::tableName() . '.*', PublicationCategoryTranslation::tableName() . '.title', 'status']);

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

        $query->leftJoin(
            PublicationItemMeta::tableName(),
            PublicationItemModel::tableName() . '.id = ' . PublicationItemMeta::tableName() . '.item_id');

        $query->leftJoin(
            PublicationCategoryTranslation::tableName(),
            PublicationItemModel::tableName() . '.category_id = ' . PublicationCategoryTranslation::tableName() . '.category_id AND ' . PublicationCategoryTranslation::tableName() . '.language = "' . \Yii::$app->language . '"');


        $query->andFilterWhere(['LIKE', PublicationItemTranslation::tableName() . '.title', $this->title]);


        $orderBy = [];

        $releaseDateOrder = null;
        switch ($this->release_date) {
            case '1':
                $releaseDateOrder = SORT_ASC;
                break;
            case '2':
                $releaseDateOrder = SORT_DESC;
                break;
        }
        if ($releaseDateOrder !== null) {
            $orderBy = ArrayHelper::merge($orderBy, ['release_date' => $releaseDateOrder]);
        }

        $categoryOrder = null;
        switch ($this->category_id) {
            case '1':
                $categoryOrder = SORT_ASC;
                break;
            case '2':
                $categoryOrder = SORT_DESC;
                break;
        }

        if ($categoryOrder !== null) {
            $orderBy = ArrayHelper::merge($orderBy, [PublicationCategoryTranslation::tableName() . '.title' => $categoryOrder]);
        }

        $statusOrder = null;
        switch ($this->status) {
            case '1':
                $statusOrder = SORT_ASC;
                break;
            case '2':
                $statusOrder = SORT_DESC;
                break;
        }

        if ($statusOrder !== null) {
            $orderBy = ArrayHelper::merge($orderBy, ['status' => $statusOrder]);
        }

        if ($this->id !== null) {
            $orderBy = ArrayHelper::merge($orderBy, [PublicationItemModel::tableName() . '.id' => $this->id === '1' ? SORT_ASC : SORT_DESC]);
        }


        $query->orderBy($orderBy);


        $query->andWhere([PublicationItemTranslation::tableName() . '.language' => \Yii::$app->language]);


        return $dataProvider;
    }


}
