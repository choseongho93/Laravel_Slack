<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use App\Repositories\Log\IDelayRepo;
use App\Traits\ResultFunction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Objects\Log\SmartCallDelayObj;

class DelayCallController extends Controller
{
    use ResultFunction;

    private $DelayCallRepo;

    function __construct(IDelayRepo $DelayCallRepo)
    {
        $this->DelayCallRepo = $DelayCallRepo;
    }

    /**
     * @OA\Post(
     *     path="/api/delay/call/log",
     *     operationId="delayDataSend",
     *     summary="30분이상 지연된 데이터 슬랙으로 로그 적재",
     *     tags={"Log"},
     *     description="30분이상 지연된 데이터 슬랙으로 로그 적재",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="fail operation",
     *         @OA\JsonContent()
     *     )
     * )
     *
     */
    public function delayDataSend(): array
    {
        /* 보낼 메시지가 없을경우 200 성공 status는 뜨나, 슬랙(slack)에 실제 메시지 발송은 하지 않음. */
        try {
            $webHootUrlkey = "웹 후크 URL Key";
            $to = 'send target';

            $SmartCallNotiApi = new SmartCallDelayObj($webHootUrlkey);

            ## 30분 동안 종료데이터가 없는 경우
            $receiveColumn = ['receive_id', 'cs_cell', 'skill_nm', 'ring_dt', 'reg_dt']; // [0] = PK
            $delayReceiveData = $this->DelayCallRepo->getDelayReceiveCallData($receiveColumn); // 30분이상 지연된 데이터 추출
            $message = $SmartCallNotiApi->setDelayReceiveMessage($delayReceiveData,$receiveColumn);

            ## 30분 동안 종료데이터가 없는 경우
            $sendColumn = ['send_id', 'cs_cell', 'skill_nm', 'send_dt', 'reg_dt']; // [0] = PK
            $delaySendData = $this->DelayCallRepo->getDelaySendCallData($sendColumn); // 30분이상 지연된 데이터 추출
            $message .= $SmartCallNotiApi->setDelaySendMessage($delaySendData,$sendColumn);

            ## 메시지 값이 있을경우
            if(!empty($message)) {
                $SmartCallNotiApi->SmartCallNotiSend($to, $message);
            }

            return $this->returnData(null, $message);

        } catch (ModelNotFoundException $e) {
            return $this->returnFailed($e->getModel());
        } catch (\Throwable $e) {
            return $this->returnFailed($e->getMessage());
        }

    }


}

