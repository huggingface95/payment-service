<?php

namespace App\Observers;

use App\Exceptions\GraphqlException;
use App\Models\TransferFIleRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransferFileRelationObserver extends BaseObserver
{

    public function creating(TransferFIleRelation|Model $model, bool $callHistory = false): bool
    {

        if (!parent::creating($model, $callHistory)) {
            return false;
        }


        if ($model->file->user->getAttributes() != Auth::user()->getAttributes()) {
            throw new GraphqlException('Access denied this file', 'use');
        }


        $this->checkAndCreateHistory($model, 'creating');

        return true;
    }

    public function updating(TransferFIleRelation|Model $model, bool $callHistory = false): bool
    {

        if (!parent::updating($model, $callHistory)) {
            return false;
        }


        if ($model->file->user->getAttributes() != Auth::user()->getAttributes()) {
            throw new GraphqlException('Access denied this file', 'use');
        }


        $this->checkAndCreateHistory($model, 'updating');

        return true;
    }

    public function saving(TransferFIleRelation|Model $model, bool $callHistory = false): bool
    {
        if (!parent::saving($model, $callHistory)) {
            return false;
        }

        if ($model->file->user->getAttributes() != Auth::user()->getAttributes()) {
            throw new GraphqlException('Access denied this file', 'use');
        }


        $this->checkAndCreateHistory($model, 'saving');

        return true;
    }
}
