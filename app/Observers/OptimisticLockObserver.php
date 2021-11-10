<?php

namespace App\Observers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\OptimisticLockException;

class OptimisticLockObserver
{
    /**
     * INSERT前
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        $version = $model->getAttribute($model->getVersionColumn());
        if (!isset($version)) {
            $model->setAttribute($model->getVersionColumn(), 0);
        }
    }

    /**
     * UPDATE前
     *
     * @param Model $model
     */
    public function updating(Model $model)
    {
        $version = $this->optimisticLockCheck($model);
        // バージョンを1加算する
        $model->setAttribute($model->getVersionColumn(), ++$version);
    }

    /**
     * Handle the User "deleting" event.
     *
     * @param  \App\Models\{Model名}  $model
     * @return void
     */
    public function deleting(Model $model)
    {
        $this->optimisticLockCheck($model);
    }

    public function restoring(Model $model)
    {
        // リストア時はUPDATEも入るためmodelのrestoringFlgをtrueにする
        $model->setRestoringFlg(true);
        $this->optimisticLockCheck($model);
    }

    public function restored(Model $model)
    {
        // リストアが完了したらmodelのrestoringFlgをfalseに戻す
        $model->setRestoringFlg(false);
    }

    private function optimisticLockCheck(Model $model) : int
    {
        // 楽観ロックチェックに使う値の取得
        $version = $model->getAttribute($model->getVersionColumn());
        $id = $model->getKey();

        // バージョン番号設定されていないとエラー
        if (!isset($version)) {
            throw new OptimisticLockException();
        }

        // 主キーがなければエラー
        if (!isset($id)) {
            throw new OptimisticLockException();
        }

        // DBから対象のレコード取得
        if (!$model->isRestoringFlg()) {
            $dbModel = $model->getMorphClass()::lockForUpdate()->find($id);
        } else {
            // restoreは削除済みレコードを取得する必要があるため「withTrashed()」してからレコード取得
            $dbModel = $model->getMorphClass()::withTrashed()->lockForUpdate()->find($id);
        }

        // 対象のレコードが取得できない場合はエラー
        if (!isset($dbModel)) {
            throw new OptimisticLockException();
        }

        // DBに設定されているバージョン番号取得
        $dbModelVersion = $dbModel->getAttribute($dbModel->getVersionColumn());
        // バージョン番号が一致しなければエラー
        if ($version != $dbModelVersion) {

            $message = 'このレコード（ID = ' .$id .'）は他のユーザによって更新されました。再読み込みして確認後、もう一度操作してください。';

            throw new OptimisticLockException($message);
        }
        return $version;
    }
}
