<?php

namespace Tests\Feature;

use App\Enum\Visibility;
use App\Models\Pastebin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchEngineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test search homepage (landing page) loads correctly.
     */
    public function test_search_homepage_renders_successfully(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertStatus(200);
        $response->assertSee('Dox');
        $response->assertSee('Me');
        $response->assertSee('Advanced Search');
    }

    /**
     * Test search capability works and highlights matching terms.
     */
    public function test_search_execution_returns_relevant_highlights(): void
    {
        // 1. Create a public pastebin
        $paste = Pastebin::create([
            'slug' => 'test-paste-slug',
            'title' => 'Important Security Database Leak',
            'content' => 'This is a sensitive payload containing critical user hashes and email addresses.',
            'description' => 'A public sample leak database.',
            'author_name' => 'Anonymous',
            'visibility' => Visibility::PUBLIC,
            'views_count' => 50,
            'download_count' => 10,
        ]);

        // 2. Perform search
        $response = $this->get(route('search.index', ['q' => 'Security payload']));

        $response->assertStatus(200);
        $response->assertSee('Important Security Database Leak');
        // Check that body query highlight is injected securely inside <mark> tags
        $response->assertSee('<mark class="bg-red-600/20 text-red-500 border border-red-900/30 px-1 rounded-sm">payload</mark>', false);
    }

    /**
     * Test private and password protected pastebins are strictly excluded.
     */
    public function test_search_excludes_private_and_password_protected_pastes(): void
    {
        // 1. Create a private paste
        Pastebin::create([
            'slug' => 'private-paste',
            'title' => 'Secret Private Database Leak',
            'content' => 'Top secret content that should not be indexed.',
            'description' => 'A private paste.',
            'author_name' => 'Anonymous',
            'visibility' => Visibility::PRIVATE,
        ]);

        // 2. Create a password-protected paste
        Pastebin::create([
            'slug' => 'password-paste',
            'title' => 'Encrypted Password Database Leak',
            'content' => 'Password encrypted content that should not be indexed.',
            'description' => 'A password protected paste.',
            'author_name' => 'Anonymous',
            'visibility' => Visibility::PUBLIC,
            'password' => bcrypt('secret123'),
        ]);

        // 3. Search for "Database Leak"
        $response = $this->get(route('search.index', ['q' => 'Database Leak']));

        $response->assertStatus(200);
        $response->assertDontSee('Secret Private Database');
        $response->assertDontSee('Encrypted Password Database');
    }

    /**
     * Test advanced search layout and execution.
     */
    public function test_advanced_search_renders_successfully(): void
    {
        $response = $this->get(route('search.advanced'));

        $response->assertStatus(200);
        $response->assertSee('Advanced Search');
        $response->assertSee('Ranking Algorithm Mode');
    }

    /**
     * Test trending feed and recent stream indexes.
     */
    public function test_feeds_render_successfully(): void
    {
        $trendingResponse = $this->get(route('search.trending'));
        $trendingResponse->assertStatus(200);
        $trendingResponse->assertSee('Trending Paste Index');

        $recentResponse = $this->get(route('search.recent'));
        $recentResponse->assertRedirect(route('pastebin.list'));
    }

    /**
     * Test raw and download actions for pastebins.
     */
    public function test_pastebin_raw_and_download_views(): void
    {
        $paste = Pastebin::create([
            'slug' => 'test-paste-actions',
            'title' => 'Downloadable Title',
            'content' => 'My super raw contents to download!',
            'description' => 'A sample test paste description.',
            'author_name' => 'Anonymous',
            'visibility' => Visibility::PUBLIC,
            'views_count' => 0,
            'download_count' => 0,
        ]);

        // Test Raw view
        $rawResponse = $this->get(route('pastebin.raw', $paste->slug));
        $rawResponse->assertStatus(200);
        $rawResponse->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $rawResponse->assertSee('My super raw contents to download!');

        // Test Download view
        $downloadResponse = $this->get(route('pastebin.download', $paste->slug));
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $downloadResponse->assertHeader('Content-Disposition', 'attachment; filename="' . $paste->slug . '.txt"');
        $downloadResponse->assertSee('My super raw contents to download!');

        // Verify download count incremented
        $paste->refresh();
        $this->assertEquals(1, $paste->download_count);
    }

    /**
     * Test anonymous user can submit an ad campaign successfully.
     */
    public function test_anonymous_ad_submission_renders_and_stores_successfully(): void
    {
        // 1. Verify /advertise GET page renders the form
        $response = $this->get(route('advertise'));
        $response->assertStatus(200);
        $response->assertSee('Submit Your Ad Request');

        // 2. Prepare mock file
        $file = \Illuminate\Http\UploadedFile::fake()->image('banner.jpg');

        // 3. Post to public /advertise route as guest
        $postResponse = $this->post(route('advertise.store'), [
            'title' => 'Anonymous Campaign Title',
            'contact' => 'Telegram: @anon',
            'target_url' => 'https://anonymous.secure',
            'image' => $file,
        ]);

        // 4. Verify redirected back with success
        $postResponse->assertRedirect();
        $postResponse->assertSessionHas('success');

        // 5. Verify data persisted under 'anonymous_advertiser' user
        $this->assertDatabaseHas('users', [
            'username' => 'anonymous_advertiser',
            'email' => 'anonymous-ads@doxme.local',
        ]);

        $this->assertDatabaseHas('ads', [
            'title' => 'Anonymous Campaign Title',
            'contact' => 'Telegram: @anon',
            'target_url' => 'https://anonymous.secure',
            'status' => 'pending',
        ]);
    }

    /**
     * Test profile show threads and posts routes with strict privacy checks.
     */
    public function test_profile_threads_and_posts_visibility_filtering(): void
    {
        // 1. Create a user
        $user = User::create([
            'username' => 'testerprofile',
            'email' => 'testerprofile@doxme.local',
            'password' => bcrypt('password'),
        ]);

        $user->identification()->create([
            'role' => \App\Enum\Role::MEMBER,
            'avatar_path' => 'upload/defaultAvatar.png',
        ]);

        // 2. Create one public and one private pastebin for this user
        $publicPaste = Pastebin::create([
            'slug' => 'test-profile-public',
            'user_id' => $user->id,
            'title' => 'Profile Public Paste',
            'content' => 'Public visibility content',
            'visibility' => Visibility::PUBLIC,
            'author_name' => $user->username,
            'views_count' => 0,
            'download_count' => 0,
        ]);

        $privatePaste = Pastebin::create([
            'slug' => 'test-profile-private',
            'user_id' => $user->id,
            'title' => 'Profile Private Paste',
            'content' => 'Secret visibility content',
            'visibility' => Visibility::PRIVATE,
            'author_name' => $user->username,
            'views_count' => 0,
            'download_count' => 0,
        ]);

        // 3. Create comments for both pastebins
        \App\Models\Comment::create([
            'user_id' => $user->id,
            'pastebin_id' => $publicPaste->id,
            'content' => 'Public paste comment content.',
        ]);

        \App\Models\Comment::create([
            'user_id' => $user->id,
            'pastebin_id' => $privatePaste->id,
            'content' => 'Private paste comment content.',
        ]);

        // 4. Guest user hits allPastebins
        $guestResponse = $this->get(route('profile.pastebins', $user->username));
        $guestResponse->assertStatus(200);
        $guestResponse->assertSee('Profile Public Paste');
        $guestResponse->assertDontSee('Profile Private Paste');

        // 5. Guest user hits allPosts
        $guestPostsResponse = $this->get(route('profile.posts', $user->username));
        $guestPostsResponse->assertStatus(200);
        $guestPostsResponse->assertSee('Public paste comment content.');
        $guestPostsResponse->assertDontSee('Private paste comment content.');

        // 6. Owner user hits allPastebins
        $ownerResponse = $this->actingAs($user)->get(route('profile.pastebins', $user->username));
        $ownerResponse->assertStatus(200);
        $ownerResponse->assertSee('Profile Public Paste');
        $ownerResponse->assertSee('Profile Private Paste');

        // 7. Owner user hits allPosts
        $ownerPostsResponse = $this->actingAs($user)->get(route('profile.posts', $user->username));
        $ownerPostsResponse->assertStatus(200);
        $ownerPostsResponse->assertSee('Public paste comment content.');
        $ownerPostsResponse->assertSee('Private paste comment content.');
    }
}
