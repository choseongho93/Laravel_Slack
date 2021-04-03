<?php


namespace App\Objects\Log;

use App\Objects\Common\BaseSlackApi;


class SmartCallDelayObj extends BaseSlackApi
{

    public function __construct($webHootUrl)
    {
        parent::__construct($webHootUrl);
    }

    public function SmartCallNotiSend($to, $message): void
    {
        parent::send($to,$message);
    }

    /****************************************************
     *  메시지 세팅1
     *
     * @param $delayReceiveData
     * @param $column
     * @return string
     ************************************************
     */
    public function setDelayReceiveMessage($delayReceiveData,$column): string
    {
        $message = '';

        // Data count
        $delayReceiveDataCount = count($delayReceiveData);

        if ($delayReceiveDataCount > 0) {
            // collection to array
            $dataToArray = $delayReceiveData->toArray();
            // Get PK max value
            $receivePkMax = $delayReceiveData->max($column[0]);

            // default message setting
            $message .= "> :warning: <  List :" . $delayReceiveDataCount . "건>:exclamation:\n";
            $message .= "> :point_right: title\n";

            // 표의 가로간격 세팅
            $maxLenData = $this->lengthInterval($dataToArray, $receivePkMax);

            // slack message에 맞춰 array 가공
            $newData = $this->dataFormat($dataToArray,$column);

            // 슬랙 api에 맞는 message로 최종 변형
            $message .= $this->slackMessageFormat($newData, $maxLenData);

        }

        return $message;

    }

    /****************************************************
     *  메시지 세팅2
     *
     * @param $delaySendData
     * @param $column
     * @return string
     *************************************************
     */
    public function setDelaySendMessage($delaySendData,$column): string
    {
        $message = '';

        // Data count
        $delaySendDataCount = count($delaySendData);

        if ($delaySendDataCount > 0) {
            // collection to array
            $dataToArray = $delaySendData->toArray();
            // Get PK max value
            $sendPkMax = $delaySendData->max($column[0]);

            // default message setting
            $message .= "> :warning: <  List :" . $delaySendDataCount . "건>:exclamation:\n";
            $message .= "> :point_right: title\n";

            // 표의 가로간격 세팅
            $maxLenData = $this->lengthInterval($dataToArray, $sendPkMax);

            // slack message에 맞춰 array 가공
            $newData = $this->dataFormat($dataToArray,$column);

            // 슬랙 api에 맞는 message로 최종 변형
            $message .= $this->slackMessageFormat($newData, $maxLenData);

        }

        return $message;

    }

    /****************************************************
     * 슬랙 API 메시지 형식에 맞춰 가공
     * $data 형태 (행열)
     * 행 : column / 열 : data value
     *
     * @param $data
     * @param $maxLenData
     * @return string
     ****************************************************/
    private function slackMessageFormat($data, $maxLenData): string
    {
        // get 최대 길이
        $maxLength = self::getMaxLength($maxLenData);
        $hypen = str_repeat("-", $maxLength); // 하이픈 변수
        $equal = str_repeat("=", $maxLength); // = 변수
        $message = "``` " . $hypen . "\n"; // 슬랙 메시지 형태 최종 변수

        $i = 0; // 행
        foreach ($data as $row) {
            $j = 0; //열
            $message .= "|";
            foreach ($row as $key => $item) {
                //데이터가 한글일경우, 2byte를 차지하기 때문에 나누기 2로 계산하여 변형
                if (preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $item)){
                    $tempText=''; // 임시 텍스트 변수
                    $pad = str_pad($tempText, $maxLenData[$j] - (strlen($item)/2)); // 글자수 차이만큼 공백
                    $text = $item.$pad;
                }else {
                    $text = str_pad($item, $maxLenData[$j]);
                }

                $message .= "" . $text . "|";
                $j++;
            }

            if ($i == 0) $message .= " \n " . $equal;

            $i++;
            $message .= "\n";
        }
        $message .= " " . $hypen . "``` \n\n\n\n";

        return $message;
    }

    /****************************************************
     * @param $data
     * @param $column
     * @return array
     ****************************************************/
    private function dataFormat($data, $column): array
    {

        $result = [$column]; // return Data

        foreach ($data as $row) {
            $temp = []; // 임시 Array

            foreach ($row as $key => $item) {
                array_push($temp,$item);
            }
            array_push($result, $temp);
        }

        return $result;

    }

    /****************************************************
     * Array의 key와 value중 최대글자수에 맞춰 표 길이 생성
     *
     * @param $data
     * @param $pkLengthMax
     * @return array
     ****************************************************/
    private function lengthInterval($data, $pkLengthMax): array
    {
        $result = []; // 결과
        $pkBool = true; // pk 확인하기 위한 변수

        foreach ($data as $row) {
            foreach ($row as $key => $item) {
                // PK는 가변이기때문에 현재 적재되어있는 데이터 기준으로 길이 조절
                if ($pkLengthMax > $item && $pkBool == true) {
                    $item = $pkLengthMax;
                }

                $keyLen = strlen($key); // 컬럼명
                $valLen = strlen($item); // 값

                $tempText = ($keyLen >= $valLen) ? $keyLen : $valLen;

                array_push($result, $tempText);
                $pkBool = false;
            }
            break;
        }

        return $result;
    }

    /****************************************************
     * 최대 글자수의 총 길이 - 테이블 간격을 위함.
     *
     * @param $maxLenData
     * @return int
     ****************************************************/
    private function getMaxLength($maxLenData): int
    {
        $maxLength = 4;
        foreach ($maxLenData as $item) {
            $maxLength += $item;
        }

        return $maxLength;
    }

}
