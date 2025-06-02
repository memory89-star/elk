<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SchedulerJobs;
use yii\db\Query;
use yii\db\Expression;
use Yii;

/**
 * SchedulerJobsSearch represents the model behind the search form of `backend\models\SchedulerJobs`.
 */
class SchedulerJobsSearch extends SchedulerJobs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['module', 'job_action', 'frequency_type', 'frequency', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = new Query();
        $query->addSelect([
            'schj.*',
            'upc.firstname as firstname',
            'upc.secondname as secondname',
            'upc.thirdname as thirdname',
            new Expression("
			case 
				when schj.frequency_type = 'minutely' then '" . Yii::t('app', 'Minutely') . "'
				when schj.frequency_type = 'hourly' then '" . Yii::t('app', 'Hourly') . "'
				when schj.frequency_type = 'daily' then '" . Yii::t('app', 'Daily') . "'
				when schj.frequency_type = 'weekly' then '" . Yii::t('app', 'Weekly') . "'
				when schj.frequency_type = 'monthly' then '" . Yii::t('app', 'Monthly') . "'
				else ''
			end frequency_type"),
        ])
            ->from(['{{%scheduler_jobs}} schj'])
            ->leftJoin(['{{%user_profile}} upc'], 'upc.user_id = schj.notify_user_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->key = 'id';

        $dataProvider->setSort([
            //'defaultOrder' => ['id' => SORT_ASC],
            'attributes' => [
                'id',
                'module',
                'job_action',
                'status',
                'frequency',
                //'frequency_type',
                'notify_user_id',
                'is_notify',
                'priority',
                'description',
            ]
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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['ilike', 'module', $this->module])
            ->andFilterWhere(['ilike', 'job_action', $this->job_action])
            ->andFilterWhere(['ilike', 'frequency_type', $this->frequency_type])
            ->andFilterWhere(['ilike', 'frequency', $this->frequency])
            ->andFilterWhere(['like', 'description', $this->description])
        ;

        //$query->orderBy(['priority' => SORT_ASC]);

        return $dataProvider;
    }
}
