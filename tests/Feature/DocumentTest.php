<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use App\Livewire\Documents\DocumentForm;
use App\Livewire\Documents\DocumentTable;
use App\Services\DocumentService;
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
        Storage::fake('google');
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
            'storage_provider' => 'google_drive',
        ]);

        $doc = Document::first();
        $this->assertNotEmpty($doc->file_path);
        Storage::disk('google')->assertExists($doc->file_path);
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
        Storage::fake('google');
        $fakeFile = UploadedFile::fake()->create('document-test.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('documents', 'google');

        $doc = Document::create([
            'title_lo' => 'ເອກະສານທົດສອບ',
            'category' => 'other',
            'file_path' => $path,
            'storage_provider' => 'google_drive',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(DocumentTable::class)
            ->call('deleteDocument', $doc->id);

        $this->assertSoftDeleted('documents', [
            'id' => $doc->id,
        ]);

        Storage::disk('google')->assertMissing($path);
    }

    public function test_deleting_legacy_local_document_uses_local_disk(): void
    {
        Storage::fake('local');
        $fakeFile = UploadedFile::fake()->create('legacy-test.pdf', 100, 'application/pdf');
        $path = $fakeFile->store('documents', 'local');

        $doc = Document::create([
            'title_lo' => 'ເອກະສານເກົ່າ',
            'category' => 'other',
            'file_path' => $path,
            'storage_provider' => 'local',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);

        Livewire::test(DocumentTable::class)
            ->call('deleteDocument', $doc->id);

        $this->assertSoftDeleted('documents', [
            'id' => $doc->id,
        ]);

        Storage::disk('local')->assertMissing($path);
    }

    public function test_document_service_uploads_updates_and_deletes_via_google_drive(): void
    {
        Storage::fake('google');
        $service = new DocumentService();

        $doc = $service->create(
            ['title_lo' => 'ເອກະສານ Drive', 'category' => 'other'],
            UploadedFile::fake()->create('first.pdf', 50, 'application/pdf')
        );

        $this->assertSame('google_drive', $doc->storage_provider);
        $this->assertNotEmpty($doc->file_path);
        Storage::disk('google')->assertExists($doc->file_path);
        $firstPath = $doc->file_path;

        $doc = $service->update(
            $doc->id,
            ['title_lo' => 'ເອກະສານ Drive'],
            UploadedFile::fake()->create('second.pdf', 50, 'application/pdf')
        );

        $this->assertSame('google_drive', $doc->storage_provider);
        $this->assertNotSame($firstPath, $doc->file_path);
        Storage::disk('google')->assertExists($doc->file_path);
        Storage::disk('google')->assertMissing($firstPath);

        $secondPath = $doc->file_path;
        $service->delete($doc->id);

        Storage::disk('google')->assertMissing($secondPath);
        $this->assertSoftDeleted('documents', ['id' => $doc->id]);
    }
}
