<?php

namespace App\Entities\Eqms;

use Illuminate\Database\Eloquent\Model;

class L_WorkshopTeam extends Model
{
    protected $table= 'l_workshop_team';
    protected $primaryKey = 'workshop_team_id';

    public function workshop() {
        return $this->belongsTo(L_Workshop::class, 'workshop_id');
    }

}
