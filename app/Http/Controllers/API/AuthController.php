<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register API
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        /**
         * get input from request data
         */

        $input = $this->processRequestInput($request);
        
        /**
         * create validation for register
         * return failed response if input data is not correct
         */
        $validator = Validator::make($input, $this->registerValidation());
        if($validator->fails()){
            return $this->jsonResponseFailed('Validation Error.', $validator->errors());       
        }

        /**
         * convert password to be unreadable
         * with bcrypt password-hashing
         */
        
        $input['password'] = bcrypt($input['password']);

        /**
         * Register the user
         */
        $user = User::create($input);

        /**
         * get name object
         */
        $success['name'] =  $user->name;

        /**
         * return success response
         */
        return $this->jsonResponseSuccess($success, 'User register successfully.');
    }
   
    /**
     * Login API
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        /**
         * get auth from request data
         */

        $auth = ['email' => $request->email, 'password' => $request->password];
        
        /**
         * if auth is true then user can login
         */
        if(Auth::attempt($auth)){ 
            $user = Auth::user(); 
            $success['name'] =  $user->name;
   
            return $this->jsonResponseSuccess($success, 'User login successfully.');
        } 
        else{ 
            /**
             * if auth is false then user failed login
             */
            return $this->jsonResponseFailed('Credential is wrong', ['error'=>' Failed Login']);
        } 
    }

     /**
     * Validation rules for register.
     *
     * @return array
     */
    protected function registerValidation()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'same:password']
        ];
    }

    /**
     * Process the input value from request
     *
     * @param Request $request
     * @return array
     */
    protected function processRequestInput(Request $request)
    {
        $input = $request->all();
        
        return $input;
    }
}
