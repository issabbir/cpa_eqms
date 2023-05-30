<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/13/20
 * Time: 12:37 PM
 */

namespace App\Contracts\Secdbms\Ims;


interface ImsIncidenceSubTypeContract
{
    public function findByType($incidentTypeId);
}