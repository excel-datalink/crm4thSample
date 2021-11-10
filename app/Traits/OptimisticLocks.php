<?php

namespace App\Traits;

use App\Observers\OptimisticLockObserver;

trait OptimisticLocks
{
    protected $restoringFlg = false;

    protected $versionColumn = 'version';

    public static function bootOptimisticLocks()
    {
        self::observe(OptimisticLockObserver::class);
    }

    public function isRestoringFlg(): bool
    {
        return $this->restoringFlg;
    }

    public function setRestoringFlg(bool $restoringFlg)
    {
        $this->restoringFlg = $restoringFlg;
    }

    public function getVersionColumn(): string
    {
        return $this->versionColumn;
    }

    public function setVersionColumn(string $versionColumn)
    {
        $this->versionColumn = $versionColumn;
    }
}
