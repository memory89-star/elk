<?php

namespace frontend\modules\elk\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\elk\models\DepartmentData;

/**
 * DepartmentDataSearch represents the model behind the search form of `use frontend\modules\risk\models\DepartmentData`.
 */
class DepartmentDataSearch extends DepartmentData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emp_department_id','emp_department_type', 'emp_department_code', 'emp_department_name', 'emp_department_full_name', 'block',
                'created_at', 'updated_at','user_last', 'user_first', 'time_create', 'time_update','manager', 'doc_num_max' ], 'safe'],
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
//        if (!isset($params['DepartmentDataSearch'])) {
//            if ($session->has('DepartmentDataSearch')) {
//                $params['DepartmentDataSearch'] = $session['DepartmentDataSearch'];
//            }
//        } else {
//            $session->set('DepartmentDataSearch', $params['DepartmentDataSearch']);
//        }
//
//        $query = DepartmentData::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
////                    'name' => SORT_ASC,
//                ],
//            ],
//        ]);
//
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
//            'emp_department_id' => $this->emp_department_id,
//            'emp_department_type' => $this->emp_department_type,
//            'doc_num_max' => $this->doc_num_max,
//            'block' => $this->block,
//            'manager' => $this->manager,
//        ]);
//
////        $query->andFilterWhere(['=', 'emp_department_id', $this->emp_department_id])
////            ->andFilterWhere(['=', 'doc_num_max', $this->doc_num_max])
////            ->andFilterWhere(['ilike', 'emp_department_code', $this->emp_department_code])
////            ->andFilterWhere(['ilike', 'emp_department_name', $this->emp_department_name])
////            ->andFilterWhere(['ilike', 'emp_department_full_name', $this->emp_department_full_name])
////            ->andFilterWhere(['ilike', 'emp_department_type', $this->emp_department_type])
////            ->andFilterWhere(['ilike', 'block', $this->block])
////            ->andFilterWhere(['ilike', 'time_create', $this->time_create])
////            ->andFilterWhere(['ilike', 'time_update', $this->time_update]);

        $session = Yii::$app->session;

        if (!isset($params['DepartmentDataSearch'])) {
            if ($session->has('DepartmentDataSearch')){
                $params['DepartmentDataSearch'] = $session['DepartmentDataSearch'];
            }
        }
        else {
            $session->set('DepartmentDataSearch', $params['DepartmentDataSearch']);
        }
//->>----4 сохранение экрана после использования сортировки по полям(1)----
        if (!isset($params['sort'])) {
            if ($session->has('DepartmentDataSearchSort')){
                $params['sort'] = $session['DepartmentDataSearchSort'];
            }
        } else {
            $session->set('DepartmentDataSearchSort', $params['sort']);
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
            $fieldSort = 'emp_department_code';
        }
//-<<----------------------------------------------------------------------
        $query = DepartmentData::find();

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
            'emp_department_id' => $this->emp_department_id,
            'doc_num_max' => $this->doc_num_max,
            'block' => $this->block,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at, //оставить
            'user_last' => $this->user_last,
            'user_first' => $this->user_first, //оставить
        ]);

        $query->andFilterWhere(['=', 'emp_department_id', $this->emp_department_id])
            ->andFilterWhere(['=', 'doc_num_max', $this->doc_num_max])
            ->andFilterWhere(['ilike', 'emp_department_code', $this->emp_department_code])
            ->andFilterWhere(['ilike', 'emp_department_name', $this->emp_department_name])
            ->andFilterWhere(['ilike', 'emp_department_full_name', $this->emp_department_full_name])
            ->andFilterWhere(['ilike', 'emp_department_type', $this->emp_department_type])
            ->andFilterWhere(['ilike', 'block', $this->block])
            ->andFilterWhere(['ilike', 'time_create', $this->time_create])
            ->andFilterWhere(['ilike', 'time_update', $this->time_update]);

        //            'id' => $this->id,
//            'emp_department_id' => $this->emp_department_id,
//            'emp_department_type' => $this->emp_department_type,
//            'doc_num_max' => $this->doc_num_max,
//            'block' => $this->block,
//            'manager' => $this->manager,

        return $dataProvider;
    }
}