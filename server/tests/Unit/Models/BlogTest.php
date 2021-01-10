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
}
