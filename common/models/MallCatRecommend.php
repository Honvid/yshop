<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mall_cat_recommend".
 *
 * @property integer $cat_id
 * @property integer $recommend_type
 */
class MallCatRecommend extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mall_cat_recommend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'recommend_type'], 'required'],
            [['cat_id', 'recommend_type'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => '分类ID',
            'recommend_type' => '推荐类型 1:精品 2:新品 3:热销',
        ];
    }

    /**
     * 通过分类ID获取推荐信息
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function find_by_cat_id($cat_id)
    {
        return self::find()->where(['cat_id' => intval($cat_id)])->asArray()->all();
    }

    /**
     * 更新分类推荐信息
     * @param  [type] $data   [description]
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public static function update_cat_recommend($data, $cat_id)
    {
        if(!empty($data)){
            $result = self::find_by_cat_id($cat_id);
            if(!empty($result)){
                foreach ($result as $key => $value) {
                    if(!in_array($value['recommend_type'], $data)){
                        self::deleteAll(['cat_id' => $value['cat_id'], 'recommend_type' => $value['recommend_type']]);
                    }else{
                        $old[] = $value['recommend_type'];
                    }
                }
                if(!empty($old)){
                    $new = array_diff($data, $old);
                    if(!empty($new)){
                        foreach ($new as $type) {
                            $query = new self();
                            $query->load(['cat_id' => $cat_id, 'recommend_type' => $type], '');
                            $query->save();
                        }
                    }
                }
            }else{
                foreach ($data as $type) {
                    $query = new self();
                    $query->load(['cat_id' => $cat_id, 'recommend_type' => $type], '');
                    $query->save();
                }
            }
        }else{
            self::deleteAll(['cat_id' => $cat_id]);
        }
    }
}
