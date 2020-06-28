<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    /**
     * [article_types 反向关联分类模型]
     * @author Pudding
     * @DateTime 2020-04-21T10:59:11+0800
     * @return   [type]                   [description]
     */
    public function article_types()
    {
    	return $this->belongsTo('App\ArticleType', 'type_id', 'id');
    }


    /* 返回带全部地址的图片*/
    public function getImagesAttribute($value)
    {
        return env("APP_URL")."/storage/".$value;
    }
}
