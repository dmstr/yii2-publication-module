<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use dosamigos\translateable\TranslateableBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\twig\ViewRenderer;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 *
 * @property string title
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
     * @throws \yii\base\InvalidConfigException
     */
    public function render(array $properties, $teaser)
    {
        $widgetTemplate = $teaser !== true ? $this->contentWidgetTemplate : $this->teaserWidgetTemplate;

        $appTwig = (array)Yii::$app->view->renderers['twig'];

        /** @var ViewRenderer $twigRenderer */
        $twigRenderer = Yii::createObject([
            'class' => ViewRenderer::class,
            'functions' => $appTwig['functions'],
            'globals' => $appTwig['globals']
        ]);

        $twigRenderer->twig->setLoader(new \Twig_Loader_Array([
            'publication' => $widgetTemplate->twig_template,
        ]));
        $twig = $twigRenderer->twig;

        return $twig->render('publication', $properties);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['translatable'] = [
            'class' => TranslateableBehavior::class,
            'relation' => 'publicationCategoryTranslations',
            'translationAttributes' => [
                'title'
            ]
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['crud'] = [
            'title',
            'content_widget_template_id',
            'teaser_widget_template_id'
        ];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['stringLengthAttribute'] = ['title','string','max' => 255];
        return $rules;
    }
}
