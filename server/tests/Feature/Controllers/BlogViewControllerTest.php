<?php

namespace Tests\Feature\Controllers;

use Facades\Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\StrRandom;
use App\Http\Middleware\BlogShowLimit;
use Illuminate\Foundation\Testing\WithoutMiddleware;


/**
 * Class BlogViewControllerTest
 * @see \App\Http\Controllers\BlogViewController
 */
class BlogViewControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test index */
    function ブログのトップページを開ける()
    {
        //$this->withoutExceptionHandling();

        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(2)->create();
        $blog3 = Blog::factory()->hasComments(3)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($blog1->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->title)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->title)
            ->assertSee($blog3->user->name)
            ->assertSee("（1件のコメント）")
            ->assertSeeInOrder([$blog3->title, $blog2->title, $blog1->title]);
    }

    /** @test */
    function ブログの一覧、非公開記事は表示されない()
    {
        Blog::factory()->closed()->create(['title' => 'ブログA']);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $this->get('/')
            ->assertOK()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC');
    }

    /** @test */
    function ブログの詳細画面を表示できる()
    {
        $blog = Blog::factory()->create();

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name);
    }

    /** @test */
    function ブログで非公開のものは、詳細表示できない()
    {

        $this->withoutMiddleware(BlogShowLimit::class);

        $blog = Blog::factory()->closed()->create();

        $this->get('blogs/'.$blog->id)
            ->assertForbidden();
    }

    /** @test */
    function factoryの観察()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue(true);
    }

    /** @test */
    function クリスマスの日は、メリークリスマスと表示される()
    {
        $this->withoutMiddleware(BlogShowLimit::class);

        $blog = Blog::factory()->create();

        Carbon::setTestNow('2020-12-24');

        $this->get('blogs/' . $blog->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス');

        Carbon::setTestNow('2020-12-25');

        $this->get('blogs/' . $blog->id)
            ->assertOk()
            ->assertSee('メリークリスマス');
    }

    /** @test */
    function ブログの詳細画面が表示でき、コメントが古い順に表示される()
    {

        $this->withoutMiddleware(BlogShowLimit::class);

        $blog = Blog::factory()->create();

        $blog = Blog::factory()->withCommentsData([
            ['created_at' => now()->sub('2 days'), 'name' => '太郎'],
            ['created_at' => now()->sub('3 days'), 'name' => '次郎'],
            ['created_at' => now()->sub('1 days'), 'name' => '三郎'],
        ])->create();

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->user->name)
            ->assertSeeInOrder(['次郎', '太郎', '三郎']);
    }

    /** @test show */
    function ブログの詳細画面で、ランダムな文字列が10文字表示される()
    {
        $this->withoutMiddleware(BlogShowLimit::class);
        $this->withoutExceptionHandling();

        $blog = Blog::factory()->create();

        $this->mock(StrRandom::class, function ($mock) {
            $mock->shouldReceive('random')->once()->with(10)->andReturn('HELLO_RAND');
        });

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee('HELLO_RAND');
    }

}
