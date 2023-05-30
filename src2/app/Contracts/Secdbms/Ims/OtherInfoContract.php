<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/20/20
 * Time: 11:13 AM
 */

namespace App\Contracts\Secdbms\Ims;


interface OtherInfoContract
{
    public function findVictims($incidentId, $incidentActionId);

    public function markVictim($count);

    public function findDrivers($incidentId, $incidentActionId);

    public function markDriver($count);

    public function findCriminals($incidentId, $incidentActionId);

    public function markCriminal($count);

    public function findVehicles($incidentId, $incidentActionId);

    public function markVehicle($count);

    public function findContainers($incidentId, $incidentActionId);

    public function markContainer($count);

    public function hasFirDetails($incidenceAction);

    public function findAttachments($incidentId, $incidentActionId);

    public function collectFiles($files, $key);
}