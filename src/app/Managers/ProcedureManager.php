<?php


namespace App\Managers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * A service manager to execture procedure
 *
 * Class ProcedureManager
 * @package App\Managers
 */
class ProcedureManager
{
    /** @var integer */
    private $statusCode;

    /** @var string */
    private $statusMessage;

    /** @var array */
    private $params;

    /**
     * Execute a procedure
     *
     * @param $procedure
     * @param Request $request
     * @return $this
     */
    public function execute($procedure, Request $request)
    {

        $sql = <<<Query
            select procedure_execute_code(:p) as res from dual
Query;
        $result = $this->params = eval(DB::selectOne($sql, ['p' => $procedure])->res);

        if (isset($result['o_status_code']))
          $this->statusCode = $result['o_status_code'];

        if (isset($result['o_status_message']))
            $this->statusMessage = $result['o_status_message'];


        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }
}
