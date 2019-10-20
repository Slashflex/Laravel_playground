<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\BlogPost;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostsWhenNothingInDatabase()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog post yet!');
    }

    public function testSee1BlogPostWhenThereIs1()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New title');
    
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->post('/posts', $params)
        ->assertStatus(302)
        ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());
    
        // Test good inputs values (>= 5 characters)
        $params = [
            'title' => 'A new name title',
            'content' => 'Content was changed'
        ];

        $this->put("/posts/{$post->id}", $params)
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
    
        $this->assertDatabaseMissing('blog_posts', $post->toArray());        
        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'A new named title'
        ]);
        // End of: Test good inputs values
    }

    public function testUpdateFail()
    {
        $post = $this->createDummyBlogPost();

        // Test empty inputs (required)
        $paramsEmpty = [
            'title' => '',
            'content' => ''
        ];

        $this->put("/posts/{$post->id}", $paramsEmpty)
        ->assertStatus(302)
        ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title field is required.');
        $this->assertEquals($messages['content'][0], 'The content field is required.');
        // End of: Test empty inputs
    }

    public function testDelete()
    {
        $post = $this->createDummyBlogPost();
        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->delete("/posts/{$post->id}")
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was deleted!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());        
    }

    private function createDummyBlogPost(): BlogPost
    {
        $post = new BlogPost();
        $post->title = 'New title';
        $post->content = 'Content of the blog post';
        $post->save();

        return $post;
    }
}
// $paramsEmpty = [
//     'title' => '',
//     'content' => ''
// ];

// $this->post('/posts', $paramsEmpty)
// ->assertStatus(302)
// ->assertSessionHas('errors');

// $messages = session('errors')->getMessages();

// $this->assertEquals($messages['title'][0], 'The title field is required.');
// $this->assertEquals($messages['content'][0], 'The content field is required.');