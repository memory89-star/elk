<?php


namespace frontend\modules\elk\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\elk\models\Reestr;

/**
 * RewardSearch represents the model behind the search form of `frontend\modules\elk\models\Reestr`.
 */

class ReestrSearch extends Reestr
{
    /**
     * @inheritdoc
     */

//    public string $date_y = '';

    public function rules()
    {
        return [
            [['date_registr','date_detection','opisan','id_department_kontrolling',
                'id_department_kontrolled','id_objects','id_significance','id_step', 'date_fact',
                'id_otvetst','id_kontrol','created_at', 'updated_at','identification_document_number',
                'incongruity', 'requirements_not_met', 'reason_modification','events_elimination','date_plan',
                'user_last', 'user_first', 'time_create', 'time_update', 'year', 'month', 'manager'], 'safe'],
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

//        $session = Yii::$app->session;
//
//        if (!isset($params['ReestrSearch'])) {
//            if ($session->has('ReestrSearch')){
//                $params['ReestrSearch'] = $session['ReestrSearch'];
//            }
//        }
//        else {
//            $session->set('ReestrSearch', $params['ReestrSearch']);
//        }
//
//        if (!isset($params['sort'])) {
//            if ($session->has('ReestrSearchSort')){
//                $params['sort'] = $session['ReestrSearchSort'];
//            }
//        } else {
//            $session->set('ReestrSearchSort', $params['sort']);
//        }
//
//        if (isset($params['sort'])) {
//            $pos = stripos($params['sort'], '-');
//            if ($pos !== false) {
//                $typeSort = SORT_DESC;
//                $fieldSort = substr($params['sort'], 1);
//            } else {
//                $typeSort = SORT_ASC;
//                $fieldSort = $params['sort'];
//            }
//        } else {
//            $typeSort = SORT_DESC;
//            $fieldSort = 'id';
//        }
//
//        $query = Reestr::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => ['defaultOrder' => [$fieldSort => $typeSort]],
//        ]);
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//
//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'date_registr' => $this->date_registr,
//            'date_detection' => $this->date_detection,
//            'opisan' => $this->opisan,
//            'id_department_kontrolling' => $this->id_department_kontrolling,
//            'id_department_kontrolled' => $this->id_department_kontrolled,
//            'id_objects' => $this->id_objects,
//            'id_significance' => $this->id_significance,
//            'id_step' => $this->id_step,
//            'date_fact' => $this->date_fact,
//            'id_kontrol' => $this->id_kontrol,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'identification_document_number' => $this->identification_document_number,
//            'incongruity' => $this->incongruity,
//            'requirements_not_met' => $this->requirements_not_met,
//            'reason_modification' => $this->reason_modification,
//            'events_elimination' => $this->events_elimination,
//            'date_plan' => $this->date_plan,
//            'year' => $this->year,
//            'month' => $this->month,
//            'manager' => $this->manager,
//        ]);
//
//        $query->andFilterWhere(['=', 'id_objects', $this->id_objects])
//            ->andFilterWhere(['=', 'id_department_kontrolling', $this->id_department_kontrolling])
//            ->andFilterWhere(['=', 'id_department_kontrolled', $this->id_department_kontrolled]);
////        ->andFilterWhere(['=', 'date_y', $this->date_y]);
////            ->andFilterWhere(['ilike', 'risk', $this->risk]);
////            ->andFilterWhere(['=', 'id_status', $this->id_status])
////            ->andFilterWhere(['=', 'id_status_after', $this->id_status_after])
////            ->andFilterWhere(['=', 'id_step', $this->id_step])
////            ->andFilterWhere(['ilike', 'eosdo_number', $this->eosdo_number])
////            ->andFilterWhere(['ilike', 'risk_identification_document_number', $this->risk_identification_document_number]);

        $session = Yii::$app->session;

        if (!isset($params['ReestrSearch'])) {
            if ($session->has('ReestrSearch')){
                $params['ReestrSearch'] = $session['ReestrSearch'];
            }
        }
        else {
            $session->set('ReestrSearch', $params['ReestrSearch']);
        }

        if (!isset($params['sort'])) {
            if ($session->has('ReestrSearchSort')){
                $params['sort'] = $session['ReestrSearchSort'];
            }
        } else {
            $session->set('ReestrSearchSort', $params['sort']);
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
            $typeSort = SORT_DESC;
            $fieldSort = 'date_detection';
        }

        $query = Reestr::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$fieldSort => $typeSort]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'date_detection' => $this->date_detection,
            'date_plan' => $this->date_plan,
            'date_fact' => $this->date_fact,
        ]);

        $query
            ->andFilterWhere(['ilike', 'opisan', $this->opisan])
            ->andFilterWhere(['=', 'id_department_kontrolling', $this->id_department_kontrolling])
            ->andFilterWhere(['=', 'id_department_kontrolled', $this->id_department_kontrolled])
            ->andFilterWhere(['=', 'id_objects', $this->id_objects])
            ->andFilterWhere(['=', 'id_significance', $this->id_significance])
            ->andFilterWhere(['=', 'id_step', $this->id_step])
            ->andFilterWhere(['=', 'id_kontrol', $this->id_kontrol])
            ->andFilterWhere(['ilike', 'incongruity', $this->incongruity])
            ->andFilterWhere(['=', 'requirements_not_met', $this->requirements_not_met])
            ->andFilterWhere(['=', 'reason_modification', $this->reason_modification])
            ->andFilterWhere(['=', 'manager', $this->manager])
            ->andFilterWhere(['ilike', 'month', $this->month])
            ->andFilterWhere(['ilike', 'year', $this->year])
            ->andFilterWhere(['ilike', 'identification_document_number', $this->identification_document_number]);


        return $dataProvider;
    }
}

