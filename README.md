# slack_laravel
Laravel에서 Slack(슬랙)으로 log 메시지 발송하는 API

## Spec
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

## image
<img src="/resources/images/image1.png" height="400"><br>



### Ref
https://github.com/gpressutto5/laravel-slack