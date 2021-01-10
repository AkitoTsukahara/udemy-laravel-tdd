<?php

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        Blog::factory()->create(['status' => Blog::CLOSE, 'title' => 'ブログA']);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $this->get('/')
            ->assertOK()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC');
    }

    /** @test */
    function factoryの観察()
    {
        $blog = Blog::factory()->create();
        $this->assertTrue(true);
    }

}
