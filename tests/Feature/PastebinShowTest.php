<?php

namespace Tests\Feature;

use App\Enum\Visibility;
use App\Models\Pastebin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PastebinShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pastebin_show_page_renders_successfully(): void
    {
        $paste = Pastebin::create([
            'slug' => 'test-paste-slug',
            'title' => 'Test Paste',
            'content' => 'Test Content',
            'description' => 'Test Description',
            'author_name' => 'Anonymous',
            'visibility' => Visibility::PUBLIC,
            'views_count' => 50,
            'download_count' => 10,
        ]);

        $response = $this->get(route('pastebin.show', $paste->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Paste');
    }
}
