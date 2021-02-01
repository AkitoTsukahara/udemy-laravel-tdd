<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SignUpController
 */
class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function ユーザ登録画面を開ける()
    {
        $this->get('signup')
            ->assertOk();
    }

    /** @test */
    function ユーザ登録できる()
    {

        //$this->withoutExceptionHandling();

        $validData = User::factory()->validData();

        $this->post('signup', $validData)
            ->assertOk();

        unset($validData['password']);

        $this->assertDatabaseHas('users', $validData);

        // パスワードの検証
        $user = User::firstWhere($validData);
        $this->assertNotNull($user);

        $this->assertTrue(\Hash::check('abcd1234', $user->password));
    }
}
