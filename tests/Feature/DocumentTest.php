<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use App\Livewire\Documents\DocumentForm;
use App\Livewire\Documents\DocumentTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Department $dept;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
        $this->dept = Department::create([
            'name_lo' => 'ຜະແນກການສຶກສາ',
            'name_en' => 'Education Department',
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_users_cannot_access_document_management(): void
    {
        $this->get(route('documents.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_see_document_list(): void
    {
        Document::create([
            'title_lo' => 'ເອກະສານການຮຽນ',
            'category' => 'other',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('documents.index'))
            ->assertStatus(200)
            ->assertSee('ເອກະສານການຮຽນ');
    }

    public function test_can_upload_document_via_livewire_component(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $fakeFile = UploadedFile::fake()->create('document-test.pdf', 100, 'application/pdf');

        Livewire::test(DocumentForm::class)
            ->set('title_lo', 'ເອກະສານໃໝ່')
            ->set('category', 'announcement')
            ->set('department_id', $this->dept->id)
            ->set('file', $fakeFile)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('documents.index'));

        $this->assertDatabaseHas('documents', [
            'title_lo' => 'ເອກະສານໃໝ່',
            'category' => 'announcement',
            'file_name' => 'document-test.pdf',
        ]);

        $doc = Document::first();
        Storage::disk('public')->assertExists($doc->file_path);
    }

    public function test_file_is_required_for_document_creation(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(DocumentForm::class)
            ->set('title_lo', 'ເອກະສານໃໝ່')
            ->set('category', 'announcement')
            ->set('file', null)
            ->call('save')
            ->assertHasErrors(['file' => 'required']);
    }

    public function test_can_toggle_document_status(): void
    {
        $doc = Document::create([
            'title_lo' => 'ເອກະສານທົດສອບ',
            'category' => 'other',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(DocumentTable::class)
            ->call('toggleActive', $doc->id);

        $this->assertFalse($doc->fresh()->is_active);
    }

    public function test_can_delete_document(): void
    {
        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->create('document-test.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('documents', 'public');

        $doc = Document::create([
            'title_lo' => 'ເອກະສານທົດສອບ',
            'category' => 'other',
            'file_path' => $path,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(DocumentTable::class)
            ->call('deleteDocument', $doc->id);

        $this->assertSoftDeleted('documents', [
            'id' => $doc->id,
        ]);

        Storage::disk('public')->assertMissing($path);
    }
}
