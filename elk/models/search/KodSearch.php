<?php


namespace frontend\modules\elk\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\elk\models\Kod;

/**
 * RewardSearch represents the model behind the search form of `frontend\modules\elk\models\Kod`.
 */

class KodSearch extends Kod
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['kod_objects', 'name', 'block', 'created_at', 'updated_at',
                'user_last', 'user_first', 'time_create', 'time_update', ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function search($params)
    {

        $session = Yii::$app->session;

        if (!isset($params['KodSearch'])) {
            if ($session->has('KodSearch')){
                $params['KodSearch'] = $session['KodSearch'];
            }
        }
        else {
            $session->set('KodSearch', $params['KodSearch']);
        }
//->>----4 сохранение экрана после использования сортировки по полям(1)----
        if (!isset($params['sort'])) {
            if ($session->has('KodSearchSort')){
                $params['sort'] = $session['KodSearchSort'];
            }
        } else {
            $session->set('KodSearchSort', $params['sort']);
        }

        if (isset($params['sort'])) {
            $pos = stripos($params['sort'], '-');
            if ($pos !== false) {
                $typeSort = SORT_DESC;
                $fieldSort = substr($params['sort'], 1);
            } else {
                $typeSort = SORT_ASC;
                $fieldSort = $params['sort'];
            }
        } else {
            $typeSort = SORT_ASC;//SORT_ASC;
            $fieldSort = 'kod_objects';
        }
//-<<----------------------------------------------------------------------
        $query = Kod::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$fieldSort => $typeSort]],//['id' => SORT_ASC]], //сортировка в форме по умолчанию (index)
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kod_objects' => $this->kod_objects,
            'block' => $this->block,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at, //оставить
            'user_last' => $this->user_last,
            'user_first' => $this->user_first, //оставить
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'block', $this->block])
            ->andFilterWhere(['ilike', 'time_create', $this->time_create])
            ->andFilterWhere(['ilike', 'time_update', $this->time_update]);




        return $dataProvider;
    }
}

