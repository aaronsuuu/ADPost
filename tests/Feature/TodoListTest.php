<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    // use DatabaseTransactions;

    public function test_truncate()
    {
        TodoList::truncate();
        Image::truncate();
    }

    public function test_store()
    {
        $response = $this->json(
            'POST',
            '/api/todolist/',
            [
                'title' => 'Test title',
                'content' => 'Test Content',
                'created_by' => 1,
            ]
        );
        $response->dump();
        $response->assertSuccessful();
    }

    public function test_upload()
    {
        $test = TodoList::first();
        $response = $this->json(
            'POST',
            '/api/todolist/uploadImg',
            [
                'todolist_id' => $test->id,
                'images' => [
                    UploadedFile::fake()->image('fake1.jpg', 50, 50),
                    UploadedFile::fake()->image('fake2.jpg', 50, 50),
                    UploadedFile::fake()->image('fake3.jpg', 50, 50),
                    UploadedFile::fake()->image('fake4.jpg', 50, 50),
                ]
            ]
        );
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_index()
    {
        $response = $this->json('GET', '/api/todolist/');
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $test = TodoList::first();
        $response = $this->json('GET', '/api/todolist/' . $test->id);
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $test = TodoList::first();
        $response = $this->json(
            'PATCH',
            '/api/todolist/' . $test->id,
            [
                'title' => 'New Test title',
                'content' => 'New Test Content',
                'created_by' => 3,
            ]
        );
        $response->dump();
        $response->assertStatus(200);
    }

    public function test_destroy()
    {
        $test = TodoList::first();
        $response = $this->json('DELETE', '/api/todolist/' . $test->id);
        $response->dump();
        $response->assertStatus(200);
    }
}
