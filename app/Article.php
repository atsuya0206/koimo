<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    protected $fillable = [
        'title',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }

    public function likes(): BelongsToMany //「いいね」における記事モデルとユーザーモデルの関係は多対多
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps(); //第一引数には関係するモデルのモデル名 第二引数には中間テーブルのテーブル
    }

    public function isLikedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->likes->where('id', $user->id)->count() //?を付けると、その引数がnullであることも許容 + whereメソッドの第一引数にキー名、第二引数に値を渡すと、その条件に一致するコレクションが返ります。
            : false;
    }

    public function getCountLikesAttribute(): int
    {
         return $this->likes->count(); //$this->likesにより、記事モデルからlikesテーブル経由で紐付いているユーザーモデルが、コレクション(配列を拡張したもの)で返る。
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

}
