<?php

namespace App\Classes\Enum;

enum HttpMethodEnum: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case DELETE = 'delete';
}
