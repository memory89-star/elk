<?php


namespace frontend\modules\elk\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\elk\models\ Events;

/**
 *  EventsSearch represents the model behind the search form of `frontend\modules\elk\models\ Events`.
 */

class EventsSearch extends  Events
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id_reestr','id_discrepancy','events','doc_num', 'date_fact',
                'created_at', 'updated_at','date_plan', 'id_kontrol',
                'user_last', 'user_first', 'time_create', 'time_update','id_otvetst' ], 'safe'],
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

        if (!isset($params['EventsSearch'])) {
            if ($session->has('EventsSearch')) {
                $params['EventsSearch'] = $session['EventsSearch'];
            }
        } else {
            $session->set('EventsSearch', $params['EventsSearch']);
        }

        $query = Events::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
//                    'name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'id_reestr' => $this->id_reestr,
//            'id_discrepancy' =>  $this->id_discrepancy,
//            'events' => $this->events,
//            'doc_num' => $this->doc_num,
//            'date_fact' => $this->date_fact,
//            'date_plan' => $this->date_plan,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'id_kontrol' => $this->id_kontrol,
//            'id_otvetst' => $this->id_otvetst,
//        ]);

//
//        $query->andFilterWhere([
//            'date_fact' => $this->date_fact,
//            'date_plan' => $this->date_plan,
//        ]);

        $query
            ->andFilterWhere(['ilike', 'events', $this->events])
            ->andFilterWhere(['=', 'id_discrepancy', $this->id_discrepancy])
            ->andFilterWhere(['=', 'id_reestr', $this->id_reestr])
            ->andFilterWhere(['=', 'id_kontrol', $this->id_kontrol])
            ->andFilterWhere(['=', 'id_otvetst', $this->id_otvetst])
            ->andFilterWhere(['=', 'date_fact', $this->date_fact])
            ->andFilterWhere(['=', 'date_plan', $this->date_plan])
            ->andFilterWhere(['ilike', 'doc_num', $this->doc_num]);


        return $dataProvider;
    }
}

