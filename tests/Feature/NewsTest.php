<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use App\Livewire\News\NewsForm;
use App\Livewire\News\NewsTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_users_cannot_access_news_management(): void
    {
        $this->get(route('news.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_see_news_list(): void
    {
        News::create([
            'title_lo' => 'ຂ່າວທົດສອບ',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('news.index'))
            ->assertStatus(200)
            ->assertSee('ຂ່າວທົດສອບ');
    }

    public function test_can_create_news_via_livewire_component(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(NewsForm::class)
            ->set('title_lo', 'ຂ່າວທົດສອບໃໝ່')
            ->set('title_en', 'New Test News')
            ->set('content_lo', 'ເນື້ອໃນຂ່າວທົດສອບ')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('news.index'));

        $this->assertDatabaseHas('news', [
            'title_lo' => 'ຂ່າວທົດສອບໃໝ່',
            'title_en' => 'New Test News',
        ]);
    }

    public function test_title_lo_is_required_for_news(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(NewsForm::class)
            ->set('title_lo', '')
            ->call('save')
            ->assertHasErrors(['title_lo' => 'required']);
    }

    public function test_can_toggle_news_status(): void
    {
        $news = News::create([
            'title_lo' => 'ຂ່າວທົດສອບ',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(NewsTable::class)
            ->call('toggleActive', $news->id);

        $this->assertFalse($news->fresh()->is_active);
    }

    public function test_can_delete_news(): void
    {
        $news = News::create([
            'title_lo' => 'ຂ່າວທົດສອບ',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(NewsTable::class)
            ->call('deleteNews', $news->id);

        $this->assertSoftDeleted('news', [
            'id' => $news->id,
        ]);
    }
}
