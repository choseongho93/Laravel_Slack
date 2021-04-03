<?php


namespace App\Repositories\Log;


use Illuminate\Support\Collection;

interface IDelayRepo
{
    /** 30분이상 지연된 데이터 추출
     * @param $column
     * @return Collection|null
     */
    public function getDelayReceiveCallData($column):?Collection;

    /** 30분이상 지연된 데이터 추출
     * @param $column
     * @return Collection|null
     */
    public function getDelaySendCallData($column):?Collection;

}

