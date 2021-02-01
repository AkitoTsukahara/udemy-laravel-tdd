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

    /** @test store */
    function 不正なデータではユーザー登録できない()
    {
        //$this->withoutExceptionHandling();
        $url = 'signup';

        $this->from('signup')->post($url, [])
            ->assertRedirect('signup');

//        app()->setlocale('testing');
//
//        $this->post($url, ['name' => ''])->assertSessionHasErrors(['name' => 'required']);
//        $this->post($url, ['name' =>str_repeat('あ', 21)])->assertSessionHasErrors(['name' => 'max']);
//        $this->post($url, ['name' =>str_repeat('あ', 20)])->assertSessionDoesntHaveErrors('name');
//
//        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
//        $this->post($url, ['email' => 'aa@bb@cc'])->assertSessionHasErrors(['email' => 'email']);
//        $this->post($url, ['email' => 'aa@ああ.いい'])->assertSessionHasErrors(['email' => 'email']);
//
//        User::factory()->create(['email' => 'aaa@bbb.net']);
//        $this->post($url, ['email' => 'aaa@bbb.net'])->assertSessionHasErrors(['email' => 'unique']);
//
//        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
//        $this->post($url, ['password' => 'abcd123'])->assertSessionHasErrors(['password' => 'min']);
//        $this->post($url, ['password' => 'abcd1234'])->assertSessionDoesntHaveErrors('password');
    }
}
