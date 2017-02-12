<?php

namespace asb\yii2\modules\contactform_3_170124\models;

use asb\yii2\modules\contactform_3_170124\models\Contactform;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ContactformSearch represents the model behind the search form about
 * `asb\yii2\modules\contactform_3_170124\models\Contactform`.
 */
class ContactformSearch extends Contactform
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'email', 'subject', 'body', 'ip', 'browser', 'create_at'], 'safe'],
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
        $query = Contactform::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (empty($params['sort'])) $query->orderBy(['create_at' => SORT_DESC]);

        $query->andFilterWhere([ //!! ignoring empty parameters
            'id' => $this->id,
            //'create_at' => $this->create_at, // like
        ]);

        if (!empty($this->user_id)) { // show specific user
            $query->andFilterWhere(['user_id' => $this->user_id]);
        } elseif ($this->user_id === '0' || $this->user_id === 0) { // show anonymous users
            $query->andWhere(['user_id' => null]) // 'user_id IS NULL'
                  ->orFilterWhere(['user_id' => 0]);
        } else { // show all users
            $query->andFilterWhere(['user_id' => null]); // ignore 'user_id'
        }

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'browser', $this->browser])
            ->andFilterWhere(['like', 'create_at', $this->create_at])
            ;

        return $dataProvider;
    }
}
