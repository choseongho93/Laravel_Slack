<?php namespace App\Traits;

trait ResultFunction
{
    protected $msg = [];
    //##################################################################################################################
    //##
    //## >> Method : Result
    //##
    //##################################################################################################################

    /**
     * Result : is Success 성공유무
     * -----------------------------------------------------------------------------------------------------------------
     * @param $result
     * @return bool : 성공(true) , 실패(false)
     */
    public function isSuccess($result)
    {
        if (empty($result)) return false;
        if (empty($result['status'])) return false;
        if (isset($result['data']) && !empty($result['data'])) {
            return $result['data'];
        } else if (isset($result['list']) && !empty($result['data'])) {
            return $result['list'];
        } else {
            return $result['status'] == 'SUCCESS';
        }
    }

    /**
     * Result : is Not Success 실패 유무
     * -----------------------------------------------------------------------------------------------------------------
     * @param $result
     * @return bool : 성공(true) , 실패(false)
     */
    public function isNotSuccess($result)
    {
        return !$this->isSuccess($result);
    }

    /**
     * Result : Success Form (성공)
     * -----------------------------------------------------------------------------------------------------------------
     * @param null $msg
     * @return array
     */
    public function returnSuccess($msg = null)
    {
        return $this->returnData(null, $msg);
    }

    /**
     * Result : Success Data Form (성공)
     * -----------------------------------------------------------------------------------------------------------------
     * @param null $data
     * @param null $msg
     * @return array
     */
    public function returnData($data = null, $msg = null)
    {
        $res = array();
        $res['status'] = 'SUCCESS';
        $res['code'] = 200;
        if (!empty($data)) $res['data'] = $data;
        if (!empty($msg)) $res['msg'] = $msg;
        return $res;
    }

    /**
     * Result : Success List Form (성공)
     * -----------------------------------------------------------------------------------------------------------------
     * @param array $data
     * @param int $total_cnt
     * @return array
     */
    public function returnList($data = array(), $total_cnt = 0, $page_row = 1, $row_cnt = 10)
    {
        $res = array();
        $res['status'] = 'SUCCESS';
        $res['code'] = 200;
        $res['total_cnt'] = $total_cnt;
        $res['list_cnt'] = count($data);
        $res['list'] = $data;
        return $res;
    }

    /**
     * Result : Failed Form (실패)
     * -----------------------------------------------------------------------------------------------------------------
     * @param null $msg
     * @param $error_code
     * @param null $debug
     * @return array
     */
    public function returnFailed($msg = null, $error_code = 400, $debug = null)
    {
        $res = array();
        $res['status'] = 'FAILED';
        $res['code'] = $error_code;
        if (!empty($msg)) $res['msg'] = $msg;
        if (!empty($debug)) $res['debug'] = $debug;
        return $res;
    }
}
