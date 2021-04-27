# Laravel_Slack
Slack(슬랙)으로 log 메시지 발송하는 API

## Tech
- laravel : 8.15
- php : 7.4
- composer package
    - 설치 명령어 : `composer require {packageName}`
    - installed
        - composer require gpressutto5/laravel-slack
        - composer require laravel/slack-notification-channel
        - composer require guzzlehttp/guzzle


## How to install
Require this package in your composer.json and update your dependencies:
```bash
composer require gpressutto5/laravel-slack
```
Since this package supports Laravel's Package Auto-Discovery you don't need to manually register the ServiceProvider.

After that, publish the configuration file:
```
php artisan vendor:publish --provider="Pressutto\LaravelSlack\ServiceProvider"
```

### Sample image screenshot
<img src="/resources/images/image1.png" height="400"><br>


### Error
1. 문제 : Fatal error: Allowed memory size of 1610612736 bytes exhausted 에러

   해결 : vi /usr/local/etc/php/7.4/conf.d/php-memory-limits.ini에서 memory_limit을 -1로 수정


### Option
1. MySQL의 데이터를 select 하기위한 DB Connection 설정


### Ref
https://github.com/gpressutto5/laravel-slack
