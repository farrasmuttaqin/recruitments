<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Todos;
use Session;

class TodoTest extends TestCase
{
    /**
     * Test Todo API
     *
     * @return  void
     */
    public function test_auth_api()
    {
        /**
         * ------------------------------- Positive Feature Test Section -------------------------------
         */

        /**
         * create simple user for todo
         */

        $this->post(url('/api/register'), [
            "_token" => Session::token(),
            "name" => 'farras',
            "email" => 'farrasmuttaqin@gmail.com',
            "password" => 'farras12345',
            "confirm_password" => 'farras12345',
        ]);
        
        $userId = User::where('email','farrasmuttaqin@gmail.com')->first();

        /**
         * input correct value for create todo
         */

        $positiveCreateTodoResponse = $this->post(url('/api/todo'), [
            "_token" => Session::token(),
            "user_id" => $userId->user_id,
            "todo_name" => 'namaTodo_123',
            "todo_description" => 'deskripsi todo',
        ]);

        /**
         * testing create todo has no errors. (Positive Test Case)
         */

        $positiveCreateTodoResponse->assertSessionHasNoErrors();
        
        /**
         * get all created todo
         */

        $positiveGetAllTodoResponse = $this->get('api/todo');
        $positiveGetAllTodoResponse->assertSessionHasNoErrors(302);
        
        /**
         * input correct value for update todo
         */

        $todo = Todos::where('todo_name','namaTodo_123')->first();

        $positiveUpdateTodoResponse = $this->put(url('/api/todo/'.$todo->id), [
            "_token" => Session::token(),
            "todo_name" => 'namaTodo_1234',
            "todo_description" => 'deskripsi todo123',
        ]);

        /**
         * testing update todo has no errors. (Positive Test Case)
         */

        $positiveUpdateTodoResponse->assertSessionHasNoErrors();

        /**
         * input correct value for destroy todo
         */

        $positiveDeleteTodoResponse = $this->delete(url('/api/todo/'.$todo->id));

        /**
         * testing update todo has no errors. (Positive Test Case)
         */

        $positiveDeleteTodoResponse->assertSessionHasNoErrors();
        
        /**
         * ------------------------------- Negative Feature Test Section -------------------------------
         */
        
        /**
         * testing input false value for create todo, without todo_description request value
         * and crete one more with wrong user id request value
         */

        $this->post(url('/api/todo'), [
            "_token" => Session::token(),
            "user_id" => $userId->user_id,
            "todo_name" => 'namaTodo_123',
        ]);

        $this->post(url('/api/todo'), [
            "_token" => Session::token(),
            "user_id" => 214214,
            "todo_name" => 'namaTodo_123',
            "todo_description" => 'deskripsi todo',
        ]);

        /**
         * testing input false value for update todo, with wrong todo id value 
         * and crete one more without todo_description request value
         */

        $this->put(url('/api/todo/1000'), [
            "_token" => Session::token(),
            "todo_name" => 'namaTodo_1234',
            "todo_description" => 'deskripsi todo123',
        ]);

        $this->put(url('/api/todo/1000'), [
            "_token" => Session::token(),
            "todo_name" => 'namaTodo_1234',
        ]);
        
        /**
         * testing input false value for Delete todo, with wrong todo id value 
         */

        $this->delete(url('/api/todo/1000'));

        /**
         * Clean Registered User
         */
        
        User::where('email', 'farrasmuttaqin@gmail.com')->delete();
    }
}
