<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Session;

class AuthTest extends TestCase
{
    /**
     * Test Auth API
     *
     * @return  void
     */
    public function test_auth_api()
    {
        /**
         * ------------------------------- Positive Feature Test Section -------------------------------
         */

        /**
         * input correct value for register
         */

        $positiveRegisterResponse = $this->post(url('/api/register'), [
            "_token" => Session::token(),
            "name" => 'farras',
            "email" => 'farrasmuttaqin@gmail.com',
            "password" => 'farras12345',
            "confirm_password" => 'farras12345',
        ]);

        /**
         * testing register has no errors. (Positive Test Case)
         */

        $positiveRegisterResponse->assertSessionHasNoErrors();
        
        /**
         * input correct value for login
         */

        $positiveLoginResponse = $this->post(url('/api/login'), [
            "_token" => Session::token(),
            "email" => 'farrasmuttaqin@gmail.com',
            "password" => 'farras12345',
        ]);

        /**
         * testing login has no errors. (Positive Test Case)
         */

        $positiveLoginResponse->assertSessionHasNoErrors();
        
        /**
         * ------------------------------- Negative Feature Test Section -------------------------------
         */
        
         /**
         * testing input false value for register, use same email as before
         */

        $this->post(url('/api/register'), [
            "_token" => Session::token(),
            "name" => 'farras',
            "email" => 'farrasmuttaqin@gmail.com',
            "password" => 'farras12345',
            "confirm_password" => 'farras12345',
        ]);
        
        /**
         * testing input false value for login, use wrong password
         */

        $this->post(url('/api/login'), [
            "_token" => Session::token(),
            "email" => 'farrasmuttaqin@gmail.com',
            "password" => '12345',
        ]);

        /**
         * Clean Registered User
         */
        
        User::where('email', 'farrasmuttaqin@gmail.com')->delete();
    }
}
