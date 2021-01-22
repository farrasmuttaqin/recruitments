<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todos;
use App\Models\User;
use Validator;
use Auth;

class TodoController extends Controller
{
    /**
     * Display all todo
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todo = Todos::join('users', 'users.user_id', '=', 'todos.user_id')
                       ->get();
        
        return $this->jsonResponseSuccess($todo, 'All todo data retrieved successfully');
    }

    /**
     * Create todo
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /**
         * get input from request data
         */

        $input = $this->processRequestInput($request);
        
        /**
         * create validation for create todo
         * return failed response if input data is not correct
         */
        $validator = Validator::make($input, $this->createValidation());
        if($validator->fails()){
            return $this->jsonResponseFailed('Validation Error.', $validator->errors());       
        }

        /**
         * Create new todo
         */
        if (User::where('user_id', $input['user_id'])->exists()) {
            $todo = Todos::create($input);
        } else {
            return $this->jsonResponseFailed('User id is not correct');
        }
        
        /**
         * get todo name object
         */
        $success['name'] =  $todo->todo_name;

        /**
         * return success response
         */
        return $this->jsonResponseSuccess($success, 'New todo created successfully.');
    }

    /**
     * update todo
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        /**
         * get input from request data
         */

        $input = $this->processRequestInput($request);
        
        /**
         * update validation for update todo
         * return failed response if input data is not correct
         */
        $validator = Validator::make($input, $this->updateValidation());
        if($validator->fails()){
            return $this->jsonResponseFailed('Validation Error.', $validator->errors());       
        }

        /**
         * Update new todo
         */
        if (Todos::where('id', $id)->exists()) {
            $todo = Todos::find($id);

            $todo->todo_name = $input['todo_name'];
            $todo->todo_description = $input['todo_description'];
            $todo->save();

        } else {
            return $this->jsonResponseFailed('Todos id is not correct');
        }
       
        /**
         * get todo name object
         */
        $success['name'] =  $input['todo_name'];

        /**
         * return success response
         */
        return $this->jsonResponseSuccess($success, 'Update todo success.');
    }

    /**
     * delete todo
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id = null)
    {
        if (Todos::where('id', $id)->exists()) {
            Todos::find($id)->delete();
        } else {
            return $this->jsonResponseFailed('Todos id is not correct');
        }
        
        /**
         * get todo id
         */
        $success['id'] =  $id;

        return $this->jsonResponseSuccess($success, 'Delete todo success.');
    }

    /**
     * Validation rules for register.
     *
     * @return array
     */
    protected function createValidation()
    {
        return [
            'user_id' => ['required'],
            'todo_name' => ['required', 'string'],
            'todo_description' => ['required', 'string']
        ];
    }

    /**
     * Validation rules for update.
     *
     * @return array
     */
    protected function updateValidation()
    {
        return [
            'todo_name' => ['required', 'string'],
            'todo_description' => ['required', 'string']
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
