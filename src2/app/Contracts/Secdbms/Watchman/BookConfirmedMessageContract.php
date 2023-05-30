<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/29/20
 * Time: 2:21 PM
 */

namespace App\Contracts\Secdbms\Watchman;


interface BookConfirmedMessageContract
{
    public function send($mobile, $information);
    public function getContent($mobile, $information) : string;
}