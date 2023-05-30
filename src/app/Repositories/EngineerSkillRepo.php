<?php


namespace App\Repositories;


use App\Entities\Eqms\EngineerSkill;
use Illuminate\Http\Request;

class EngineerSkillRepo
{
    protected  $engineerskill;
    protected $request;

    /**
     *
     * EngineerSkillRepo constructor.
     * @param EngineerSkill $engineerskill
     */
    public function __construct(EngineerSkill $engineerskill)
    {
        $this->engineerskill = $engineerskill;
    }

    /**
     * Find all engineerskills for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->engineerskill->orderBy('service_skill_id', 'desc')->get();
    }

    /**
     * Find engineerskill specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->engineerskill->where('service_skill_id', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getEngineerSkill() {
        return $this->engineerskill;
    }
}
