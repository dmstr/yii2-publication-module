<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use dosamigos\translateable\TranslateableBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\twig\ViewRenderer;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 */
class PublicationCategory extends BasePublicationCategory
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_category}}';
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->title;
    }

    /**
     * @param $properties
     * @param $teaser
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(array $properties, $teaser)
    {
        $widgetTemplate = $teaser !== true ? $this->contentWidgetTemplate : $this->teaserWidgetTemplate;

        /** @var ViewRenderer $twigRenderer */
        $twigRenderer = Yii::createObject(Yii::$app->view->renderers['twig']);
        
        $twigRenderer->twig->setLoader(new \Twig_Loader_Array([
            'publication' => $widgetTemplate->twig_template,
        ]));
        $twig = $twigRenderer->twig;

        return $twig->render('publication', $properties);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'translatable' => [
                'class' => TranslateableBehavior::className(),
                'relation' => 'publicationCategoryTranslations',
                'translationAttributes' => [
                    'title',
                ],
            ],
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'crud' => [
                'title',
                'content_widget_template_id',
                'teaser_widget_template_id'
            ]
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['title'], 'string', 'max' => 80],
            ]
        );
    }
}
