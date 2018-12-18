<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/16
 * Time: 9:57
 */
/*
$token = array(
    "iss" => "http://example.org",   #非必须。issuer 请求实体，可以是发起请求的用户的信息，也可是jwt的签发者。
    "iat" => 1356999524,                #非必须。issued at。 token创建时间，unix时间戳格式
    "exp" => "1548333419",            #非必须。expire 指定token的生命周期。unix时间戳格式
    "aud" => "http://example.com",   #非必须。接收该JWT的一方。
    "sub" => "jrocket@example.com",  #非必须。该JWT所面向的用户
    "nbf" => 1357000000,   # 非必须。not before。如果当前时间在nbf里的时间之前，则Token不被接受；一般都会留一些余地，比如几分钟。
    "jti" => '222we',     # 非必须。JWT ID。针对当前token的唯一标识

    "GivenName" => "Jonny", # 自定义字段
    "Surname" => "Rocket",  # 自定义字段
    "Email" => "jrocket@example.com", # 自定义字段
    "Role" => ["Manager", "Project Administrator"] # 自定义字段
);*/

return [
    'key'=>env('JWT_KEY',env('APP_KEY')),
    'iss'=>'heijiang.top',
    'aud'=>'vue.heijiang.top',
    'sub'=>'vue@heijiang.top',
    'alg' => env('JWT_ALG','HS256'),
    'exp'=>env('JWT_EXP','600'),
];