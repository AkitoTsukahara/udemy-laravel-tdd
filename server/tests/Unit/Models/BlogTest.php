<?php

namespace Tests\Unit\Models;

//use PHPUnit\Framework\TestCase;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function userリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(User::class, $blog->user);
    }

    /**
     * @test
     */
    function commentリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(Collection::class, $blog->comments);
    }

    /** @test */
    function ブログの公開・非公開のscope()
    {
        $bloag1 = Blog::factory()->closed()->create(['title' => 'ブログA']);
        $bloag2 = Blog::factory()->create(['title' => 'ブログB']);
        $bloag3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();

        $this->assertFalse($blogs->contains($bloag1));
        $this->assertTrue($blogs->contains($bloag2));
        $this->assertTrue($blogs->contains($bloag3));
    }

    /** @test */
    function ブログ非公開時はtrue、公開時にはfalseを返す()
    {
        $blog = Blog::factory()->closed()->create();
        $this->assertTrue($blog->isClosed());

        $blog = Blog::factory()->create();
        $this->assertFalse($blog->isClosed());
    }
}
