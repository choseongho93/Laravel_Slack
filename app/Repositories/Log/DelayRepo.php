<?php


namespace App\Repositories\Log;

use App\Models\SmartCall\Service\SysCallReceiveMgt;
use App\Models\SmartCall\Service\SysCallSendMgt;
use Illuminate\Support\Collection;

class DelayRepo implements IDelayRepo
{
    /** 관리 테이블
     * @var CallReceiveMgt
     * @var CallSendMgt
     */
    private $SysCallReceiveMgt;
    private $SysCallSendMgt;

    public function __construct
    (
        CallReceiveMgt $CallReceiveMgt,
        CallSendMgt $CallSendMgt
    )
    {
        $this->CallReceiveMgt = $CallReceiveMgt;
        $this->CallSendMgt = $CallSendMgt;
    }

    ## 데이터가 30분이상 없는 경우
    public function getDelayReceiveCallData($column) : ? Collection
    {
        return $this->CallReceiveMgt->select($column)
            ->Where('...')
            ->get();

    }

    ##  데이터가 30분이상 없는 경우
    public function getDelaySendCallData($column) : ? Collection
    {
        return $this->CallSendMgt->select($column)
            ->Where('...')
            ->get();

    }

}

