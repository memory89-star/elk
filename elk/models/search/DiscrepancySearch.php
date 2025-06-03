<?php


namespace frontend\modules\elk\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\elk\models\Discrepancy;

/**
 * DiscrepancySearch represents the model behind the search form of `frontend\modules\elk\models\Discrepancy`.
 */

class DiscrepancySearch extends Discrepancy
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id_reestr','doc_num','discrepancy','created_at', 'updated_at',
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

//        $session = Yii::$app->session;
//
//        if (!isset($params['DiscrepancySearch'])) {
//            if ($session->has('DiscrepancySearch')) {
//                $params['DiscrepancySearch'] = $session['DiscrepancySearch'];
//            }
//        } else {
//            $session->set('DiscrepancySearch', $params['DiscrepancySearch']);
//        }
//
//        $query = Discrepancy::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
////                    'name' => SORT_ASC,
//                ],
//            ],
//        ]);

        $session = Yii::$app->session;

        if (!isset($params['DiscrepancySearch'])) {
            if ($session->has('DiscrepancySearch')){
                $params['DiscrepancySearch'] = $session['DiscrepancySearch'];
            }
        }
        else {
            $session->set('DiscrepancySearch', $params['DiscrepancySearch']);
        }

        if (!isset($params['sort'])) {
            if ($session->has('DiscrepancySearchSearchSort')){
                $params['sort'] = $session['DiscrepancySearchSearchSort'];
            }
        } else {
            $session->set('DiscrepancySearchSearchSort', $params['sort']);
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
            $typeSort = SORT_ASC;
            $fieldSort = 'discrepancy';
        }

        $query = Discrepancy::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$fieldSort => $typeSort]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'id_reestr' => $this->id_reestr,
//            'doc_num' => $this->doc_num,
//            'discrepancy' => $this->discrepancy,
//        ]);


        $query
            ->andFilterWhere(['ilike', 'doc_num', $this->doc_num])
            ->andFilterWhere(['=', 'id_reestr', $this->id_reestr])
            ->andFilterWhere(['ilike', 'discrepancy', $this->discrepancy]);


        return $dataProvider;
    }
}

