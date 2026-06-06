<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use App\Livewire\HeroSlides\HeroSlideForm;
use App\Livewire\HeroSlides\HeroSlideTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HeroSlideTest extends TestCase
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

    public function test_unauthenticated_users_cannot_access_hero_slides_management(): void
    {
        $this->get(route('hero-slides.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_see_slides_list(): void
    {
        HeroSlide::create([
            'title_lo' => 'ສະໄລ້ທົດສອບ',
            'image_path' => 'hero-slides/test.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('hero-slides.index'))
            ->assertStatus(200)
            ->assertSee('ສະໄລ້ທົດສອບ');
    }

    public function test_can_create_slide_via_livewire_component(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $fakeImage = UploadedFile::fake()->image('slide-test.jpg', 1920, 1080);

        Livewire::test(HeroSlideForm::class)
            ->set('title_lo', 'ສະໄລ້ໃໝ່')
            ->set('subtitle_lo', 'ຄຳອະທິບາຍໃໝ່')
            ->set('image', $fakeImage)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('hero-slides.index'));

        $this->assertDatabaseHas('hero_slides', [
            'title_lo' => 'ສະໄລ້ໃໝ່',
            'subtitle_lo' => 'ຄຳອະທິບາຍໃໝ່',
        ]);

        $slide = HeroSlide::first();
        Storage::disk('public')->assertExists($slide->image_path);
    }

    public function test_image_is_required_for_slide_creation(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(HeroSlideForm::class)
            ->set('title_lo', 'ສະໄລ້ໃໝ່')
            ->set('image', null)
            ->call('save')
            ->assertHasErrors(['image' => 'required']);
    }

    public function test_can_toggle_slide_status(): void
    {
        $slide = HeroSlide::create([
            'title_lo' => 'ສະໄລ້ທົດສອບ',
            'image_path' => 'hero-slides/test.jpg',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(HeroSlideTable::class)
            ->call('toggleActive', $slide->id);

        $this->assertFalse($slide->fresh()->is_active);
    }

    public function test_can_delete_slide(): void
    {
        Storage::fake('public');
        $fakeImage = UploadedFile::fake()->image('slide-test.jpg');
        $path = $fakeImage->store('hero-slides', 'public');

        $slide = HeroSlide::create([
            'title_lo' => 'ສະໄລ້ທົດສອບ',
            'image_path' => $path,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(HeroSlideTable::class)
            ->call('deleteSlide', $slide->id);

        $this->assertSoftDeleted('hero_slides', [
            'id' => $slide->id,
        ]);

        Storage::disk('public')->assertMissing($path);
    }
}
