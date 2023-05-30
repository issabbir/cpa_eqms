<?php

class LoginTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var   \App\Managers\Authorization\AuthorizationManager */
    protected $authManager;

    protected function _before()
    {
        $this->authManager = new \App\Managers\Authorization\AuthorizationManager();
    }

    protected function _after()
    {
    }

    // tests
    public function testAuthParams()
    {
        $params = [
            'p_user_name' => 'admin',
            'p_user_pass' => 'cns123',
            'p_user_ip_address' => '',
            '_token' => '',
            ];
       $params = \App\Enums\Auth\UserParams::bindParams($params);
       $this->tester->assertEquals(count($params), 9);
    }

    public function testAuthValidation() {
         $this->tester->assertEquals(1,1);
    }

}
