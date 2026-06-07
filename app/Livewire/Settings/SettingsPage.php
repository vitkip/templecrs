<?php

namespace App\Livewire\Settings;

use App\Models\Department;
use App\Models\Personnel;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('ການຕັ້ງຄ່າ / Settings')]
class SettingsPage extends Component
{
    use WithFileUploads;

    public string $activeTab = 'organization';

    // ─── Organization ───────────────────────────────────────
    public string $org_name_lo = '';
    public string $org_name_en = '';
    public string $org_address_lo = '';
    public string $org_address_en = '';
    public string $org_phone = '';
    public string $org_email = '';
    public string $org_website = '';
    public string $org_established_year = '';
    public $org_logo = null;
    public ?string $existing_logo_url = null;

    // ─── System ─────────────────────────────────────────────
    public string $default_locale = 'lo';
    public int $per_page = 15;
    public bool $show_english_names = true;

    // ─── Department Form ─────────────────────────────────────
    public bool $showDeptForm = false;
    public ?int $editDeptId = null;
    public string $dept_name_lo = '';
    public string $dept_name_en = '';
    public string $dept_description_lo = '';
    public string $dept_description_en = '';
    public ?int $dept_head_id = null;
    public int $dept_sort_order = 0;
    public bool $dept_is_active = true;

    /* ──────────────────────────────────────────────────────── */

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->loadOrganization();
        $this->loadSystem();
    }

    private function loadOrganization(): void
    {
        $this->org_name_lo          = Setting::get('org_name_lo', '');
        $this->org_name_en          = Setting::get('org_name_en', '');
        $this->org_address_lo       = Setting::get('org_address_lo', '');
        $this->org_address_en       = Setting::get('org_address_en', '');
        $this->org_phone            = Setting::get('org_phone', '');
        $this->org_email            = Setting::get('org_email', '');
        $this->org_website          = Setting::get('org_website', '');
        $this->org_established_year = Setting::get('org_established_year', '');
        $this->existing_logo_url    = Setting::get('org_logo_url') ?: null;
    }

    private function loadSystem(): void
    {
        $this->default_locale      = Setting::get('default_locale', 'lo');
        $this->per_page            = (int) Setting::get('per_page', 15);
        $this->show_english_names  = (bool) Setting::get('show_english_names', '1');
    }

    /* ── Organization ──────────────────────────────────────── */

    public function saveOrganization(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            'org_name_lo'           => 'required|string|max:200',
            'org_name_en'           => 'nullable|string|max:200',
            'org_address_lo'        => 'nullable|string|max:500',
            'org_address_en'        => 'nullable|string|max:500',
            'org_phone'             => 'nullable|string|max:50',
            'org_email'             => 'nullable|email|max:120',
            'org_website'           => 'nullable|url|max:300',
            'org_established_year'  => 'nullable|digits:4|integer|min:1800|max:2100',
            'org_logo'              => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($this->org_logo) {
            $old = Setting::get('org_logo_url');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $this->org_logo->store('settings/logo', 'public');
            Setting::set('org_logo_url', $path, 'organization');
            $this->existing_logo_url = $path;
            $this->org_logo = null;
        }

        Setting::setMany([
            'org_name_lo'          => $this->org_name_lo,
            'org_name_en'          => $this->org_name_en,
            'org_address_lo'       => $this->org_address_lo,
            'org_address_en'       => $this->org_address_en,
            'org_phone'            => $this->org_phone,
            'org_email'            => $this->org_email,
            'org_website'          => $this->org_website,
            'org_established_year' => $this->org_established_year,
        ], 'organization');

        session()->flash('settings_message', 'ບັນທຶກຂໍ້ມູນອົງການສຳເລັດ / Organization info saved.');
    }

    /* ── System ────────────────────────────────────────────── */

    public function saveSystem(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            'default_locale'     => 'required|in:lo,en',
            'per_page'           => 'required|integer|min:5|max:100',
            'show_english_names' => 'boolean',
        ]);

        Setting::setMany([
            'default_locale'     => $this->default_locale,
            'per_page'           => (string) $this->per_page,
            'show_english_names' => $this->show_english_names ? '1' : '0',
        ], 'system');

        session()->flash('settings_message', 'ບັນທຶກການຕັ້ງຄ່າລະບົບສຳເລັດ / System settings saved.');
    }

    /* ── Departments ───────────────────────────────────────── */

    public function openAddDept(): void
    {
        $this->resetDeptForm();
        $nextOrder = Department::withTrashed(false)->max('sort_order') + 1;
        $this->dept_sort_order = $nextOrder;
        $this->showDeptForm = true;
    }

    public function editDept(int $id): void
    {
        $dept = Department::withTrashed(false)->findOrFail($id);
        $this->editDeptId          = $id;
        $this->dept_name_lo        = $dept->name_lo;
        $this->dept_name_en        = $dept->name_en ?? '';
        $this->dept_description_lo = $dept->description_lo ?? '';
        $this->dept_description_en = $dept->description_en ?? '';
        $this->dept_head_id        = $dept->head_id;
        $this->dept_sort_order     = $dept->sort_order;
        $this->dept_is_active      = $dept->is_active;
        $this->showDeptForm        = true;
    }

    public function saveDept(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            'dept_name_lo'        => 'required|string|max:200',
            'dept_name_en'        => 'nullable|string|max:200',
            'dept_description_lo' => 'nullable|string|max:1000',
            'dept_description_en' => 'nullable|string|max:1000',
            'dept_head_id'        => 'nullable|exists:personnel,id',
            'dept_sort_order'     => 'integer|min:0|max:999',
            'dept_is_active'      => 'boolean',
        ]);

        $data = [
            'name_lo'        => $this->dept_name_lo,
            'name_en'        => $this->dept_name_en ?: null,
            'description_lo' => $this->dept_description_lo ?: null,
            'description_en' => $this->dept_description_en ?: null,
            'head_id'        => $this->dept_head_id,
            'sort_order'     => $this->dept_sort_order,
            'is_active'      => $this->dept_is_active,
        ];

        if ($this->editDeptId) {
            Department::findOrFail($this->editDeptId)->update($data);
            session()->flash('settings_message', 'ແກ້ໄຂພາກສ່ວນສຳເລັດ / Department updated.');
        } else {
            Department::create($data);
            session()->flash('settings_message', 'ເພີ່ມພາກສ່ວນໃໝ່ສຳເລັດ / Department added.');
        }

        $this->showDeptForm = false;
        $this->resetDeptForm();
    }

    public function cancelDeptForm(): void
    {
        $this->showDeptForm = false;
        $this->resetDeptForm();
    }

    public function toggleDeptActive(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $dept = Department::withTrashed(false)->findOrFail($id);
        $dept->update(['is_active' => !$dept->is_active]);
    }

    public function deleteDept(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $dept = Department::withTrashed(false)->findOrFail($id);

        if ($dept->personnel()->count() > 0) {
            session()->flash('settings_error', 'ບໍ່ສາມາດລຶບໄດ້ — ພາກສ່ວນນີ້ຍັງມີບຸກຄະລາກອນຢູ່ / Cannot delete — department still has personnel.');
            return;
        }

        $dept->delete();
        session()->flash('settings_message', 'ລຶບພາກສ່ວນສຳເລັດ / Department deleted.');
    }

    private function resetDeptForm(): void
    {
        $this->editDeptId          = null;
        $this->dept_name_lo        = '';
        $this->dept_name_en        = '';
        $this->dept_description_lo = '';
        $this->dept_description_en = '';
        $this->dept_head_id        = null;
        $this->dept_sort_order     = 0;
        $this->dept_is_active      = true;
        $this->resetErrorBag();
    }

    /* ── Render ────────────────────────────────────────────── */

    public function render()
    {
        $departments = Department::withTrashed(false)
            ->withCount(['personnel as active_count' => fn ($q) => $q->where('is_active', true)])
            ->withCount('personnel as total_count')
            ->with('head')
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get();

        $personnelList = Personnel::active()
            ->ordered()
            ->get(['id', 'name_lo', 'name_en', 'position_lo']);

        return view('livewire.settings.page', compact('departments', 'personnelList'));
    }
}
