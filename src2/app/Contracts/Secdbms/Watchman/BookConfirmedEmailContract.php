<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/29/20
 * Time: 1:28 PM
 */

namespace App\Contracts\Secdbms\Watchman;

use App\Mail\BookConfirmed;

interface BookConfirmedEmailContract
{
    public function send($toEmail, $information);
    public function prepare($information) : BookConfirmed;
}