<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    /**
     * 顧客へのリレーション
     *
     * @var array
     */
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    /**
     * 商品へのリレーション
     *
     * @var array
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * 設定情報へのリレーション
     *
     * @var Profile
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
     * 見積
     *
     * @var array
     */
    public function estimates()
    {
        return $this->hasMany('App\Estimate');
    }

    /**
     * 見積明細
     *
     * @var array
     */
    public function estimate_details()
    {
        return $this->hasMany('App\EstimateDetail');
    }

    public function getJWTIdentifier()
    {
        // JWT トークンに保存する ID を返す
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // JWT トークンに埋め込む追加の情報を返す
        return [];
    }
}
