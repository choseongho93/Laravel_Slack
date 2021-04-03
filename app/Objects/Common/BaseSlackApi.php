<?php


namespace App\Objects\Common;

use Pressutto\LaravelSlack\Facades\Slack;


abstract class BaseSlackApi
{
    private $webHootUrlKey;

    function __construct($webHootUrlKey){
        $this->webHootUrlKey = $webHootUrlKey;

        $this->setWebHookUrl();
    }

    /****************************************************
     * 슬랙 webhook config 정보
     *
     ***************************************************/
    private function setWebHookUrl ()
    {
        $urlList = [
            "delay_call" => "https://hooks.slack.com/...슬랙_웹후크_URL"
        ];

        config(['laravel-slack.slack_webhook_url' => $urlList[$this->webHootUrlKey]]);

    }

    /****************************************************
     * 슬랙 메시지 발송
     * 받는사람 / Ex) ['@zoe', '@amy', '@mia']도 가능
     *
     ***************************************************
     * @param string $to
     * @param string $message
     */
    protected function send($to, $message) : void
    {
        Slack::to($to)->send($message);
    }

}
