<?php

namespace App\Models\Interfaces;


interface HistoryInterface
{

    public function enableHistory(): bool;

    public function getHistoryColumns(): array;

    public function getHistoryActions(): array;

}
