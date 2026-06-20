<div class="space-y-8">

    {{-- ══════════════════════ HEADER ══════════════════════ --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">{{ __('messages.dashboard') }}</h1>
            <p class="text-body-sm text-on-surface-variant mt-0.5">
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                · {{ __('messages.welcome') }}, <span class="font-semibold text-primary">{{ auth()->user()->name }}</span>
            </p>
        </div>
        <span class="hidden sm:flex items-center gap-2 text-label-sm text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-full">
            <span class="material-symbols-outlined text-base text-primary">badge</span>
            {{ auth()->user()->role_label }}
        </span>
    </div>

    {{-- ══════════════════════ SUPERADMIN / ADMIN SECTION ══════════════════════ --}}
    @if (auth()->user()->isAdmin())

        {{-- ── Stat Cards Row ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Personnel --}}
            <a href="{{ route('personnel.index') }}"
               class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-xl">group</span>
                    </div>
                    <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">
                        {{ $personnel_active }} {{ __('messages.active') }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-on-surface">{{ number_format($personnel_total) }}</p>
                <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.personnel') }}</p>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant">
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">self_improvement</span>
                        {{ $personnel_monks }} {{ __('messages.monks') }}
                    </span>
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">person</span>
                        {{ $personnel_lay }} {{ __('messages.laypersons') }}
                    </span>
                </div>
            </a>

            {{-- News --}}
            <a href="{{ route('news.index') }}"
               class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-xl">newspaper</span>
                    </div>
                    <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                        {{ $news_published }} {{ __('messages.published') }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-on-surface">{{ number_format($news_total) }}</p>
                <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.news') }}</p>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant">
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">edit_note</span>
                        {{ $news_draft }} {{ __('messages.draft') }}
                    </span>
                </div>
            </a>

            {{-- Documents --}}
            <a href="{{ route('documents.index') }}"
               class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary text-xl">description</span>
                    </div>
                    <span class="text-[10px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">
                        {{ $docs_active }} {{ __('messages.active') }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-on-surface">{{ number_format($docs_total) }}</p>
                <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.documents') }}</p>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant">
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">download</span>
                        {{ number_format($docs_downloads) }} {{ __('messages.downloads') }}
                    </span>
                </div>
            </a>

            {{-- System (superadmin only) or placeholder --}}
            @if (auth()->user()->isSuperAdmin())
                <a href="{{ route('users.index') }}"
                   class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-error text-xl">manage_accounts</span>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-on-surface">{{ number_format($users_total) }}</p>
                    <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.users') }}</p>
                    <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant">
                        <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">category</span>
                            {{ $departments_total }} {{ __('messages.departments') }}
                        </span>
                    </div>
                </a>
            @else
                <a href="{{ route('personnel.create') }}"
                   class="group bg-primary/5 border-2 border-dashed border-primary/30 rounded-2xl p-5 flex flex-col items-center justify-center gap-2 hover:bg-primary/10 hover:border-primary/50 transition-all duration-200 min-h-[140px]">
                    <span class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">person_add</span>
                    </span>
                    <p class="text-label-md font-bold text-primary text-center">{{ __('messages.new_entry') }}</p>
                </a>
            @endif
        </div>

        {{-- ── Recent rows (3-column grid) ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Recent Personnel --}}
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                    <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">group</span>
                        {{ __('messages.personnel') }}
                    </h3>
                    <a href="{{ route('personnel.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-outline-variant">
                    @forelse ($recent_personnel as $p)
                        <li>
                            <a href="{{ route('personnel.show', $p->id) }}"
                               class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container transition-colors">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden">
                                    @if ($p->photo_url)
                                        <img src="{{ Storage::url($p->photo_url) }}" alt="" class="w-full h-full object-cover" />
                                    @else
                                        <span class="material-symbols-outlined text-primary text-sm">person</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">{{ $p->display_name }}</p>
                                    <p class="text-[11px] text-on-surface-variant truncate">{{ $p->display_position ?: '—' }}</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                    @endforelse
                </ul>
            </div>

            {{-- Recent News --}}
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                    <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-base">newspaper</span>
                        {{ __('messages.news') }}
                    </h3>
                    <a href="{{ route('news.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-outline-variant">
                    @forelse ($recent_news as $n)
                        <li>
                            <a href="{{ route('news.show', $n->id) }}"
                               class="flex items-start gap-3 px-5 py-3 hover:bg-surface-container transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-secondary text-sm">article</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">{{ $n->title }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $n->published_at?->format('d/m/Y') }}</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                    @endforelse
                </ul>
            </div>

            {{-- Recent Documents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                    <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-tertiary text-base">description</span>
                        {{ __('messages.documents') }}
                    </h3>
                    <a href="{{ route('documents.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-outline-variant">
                    @forelse ($recent_docs as $d)
                        <li>
                            <a href="{{ route('documents.show', $d->id) }}"
                               class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-tertiary/10 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-tertiary text-sm">{{ $d->icon }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">{{ $d->title_lo ?? $d->title_en }}</p>
                                    <p class="text-[11px] text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[10px]">download</span>
                                        {{ number_format($d->download_count) }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                    @endforelse
                </ul>
            </div>

        </div>

    @endif

    {{-- ══════════════════════ STAFF SECTION (docs + news only) ══════════════════════ --}}
    @if (auth()->user()->isStaff())

        <div class="grid grid-cols-2 gap-4">

            {{-- News --}}
            <a href="{{ route('news.index') }}"
               class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary text-xl">newspaper</span>
                    </div>
                    <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full">
                        {{ $news_published ?? 0 }} {{ __('messages.published') }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-on-surface">{{ number_format($news_total ?? 0) }}</p>
                <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.news') }}</p>
            </a>

            {{-- Documents --}}
            <a href="{{ route('documents.index') }}"
               class="group bg-white rounded-2xl p-5 shadow-sm border border-outline-variant hover:shadow-md hover:border-primary/30 transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-tertiary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary text-xl">description</span>
                    </div>
                    <span class="text-[10px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">
                        {{ $docs_active ?? 0 }} {{ __('messages.active') }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-on-surface">{{ number_format($docs_total ?? 0) }}</p>
                <p class="text-label-sm text-on-surface-variant mt-0.5">{{ __('messages.documents') }}</p>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-outline-variant">
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">download</span>
                        {{ number_format($docs_downloads ?? 0) }} {{ __('messages.downloads') }}
                    </span>
                </div>
            </a>

        </div>

        {{-- Staff recent items --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Recent News --}}
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                    <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-base">newspaper</span>
                        {{ __('messages.news') }}
                    </h3>
                    <a href="{{ route('news.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-outline-variant">
                    @forelse ($recent_news ?? [] as $n)
                        <li>
                            <a href="{{ route('news.show', $n->id) }}"
                               class="flex items-start gap-3 px-5 py-3 hover:bg-surface-container transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-secondary text-sm">article</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">{{ $n->title }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $n->published_at?->format('d/m/Y') }}</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                    @endforelse
                </ul>
            </div>

            {{-- Recent Documents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                    <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-tertiary text-base">description</span>
                        {{ __('messages.documents') }}
                    </h3>
                    <a href="{{ route('documents.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-outline-variant">
                    @forelse ($recent_docs ?? [] as $d)
                        <li>
                            <a href="{{ route('documents.show', $d->id) }}"
                               class="flex items-center gap-3 px-5 py-3 hover:bg-surface-container transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-tertiary/10 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-tertiary text-sm">{{ $d->icon }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">{{ $d->title_lo ?? $d->title_en }}</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                    @endforelse
                </ul>
            </div>

        </div>

    @endif

    {{-- ══════════════════════ FINANCE SECTION (superadmin + manager) ══════════════════════ --}}
    @if (auth()->user()->canManageFinance())

        <div class="space-y-6">

            {{-- Finance stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Income --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-success text-xl">trending_up</span>
                        </div>
                        <p class="text-label-sm text-on-surface-variant">{{ __('messages.income') }} ({{ now()->format('M Y') }})</p>
                    </div>
                    <p class="text-2xl font-bold text-success">{{ number_format((float) $month_income, 0, '.', ',') }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">ກີບ</p>
                </div>

                {{-- Expense --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-error text-xl">trending_down</span>
                        </div>
                        <p class="text-label-sm text-on-surface-variant">{{ __('messages.expense') }} ({{ now()->format('M Y') }})</p>
                    </div>
                    <p class="text-2xl font-bold text-error">{{ number_format((float) $month_expense, 0, '.', ',') }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">ກີບ</p>
                </div>

                {{-- Balance --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-xl">account_balance_wallet</span>
                        </div>
                        <p class="text-label-sm text-on-surface-variant">{{ __('messages.balance') }} ({{ now()->format('M Y') }})</p>
                    </div>
                    <p class="text-2xl font-bold {{ $month_balance >= 0 ? 'text-success' : 'text-error' }}">
                        {{ $month_balance >= 0 ? '+' : '' }}{{ number_format((float) $month_balance, 0, '.', ',') }}
                    </p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">ກີບ</p>
                </div>

            </div>

            {{-- Chart + Recent Transactions --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- Bar Chart --}}
                <div class="lg:col-span-3 bg-white rounded-2xl p-5 shadow-sm border border-outline-variant">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-base">bar_chart</span>
                            {{ __('messages.monthly_summary') }} {{ $current_year }}
                        </h3>
                        <a href="{{ route('finance.index') }}" class="text-[11px] text-primary hover:underline">{{ __('messages.view_all') }}</a>
                    </div>
                    <canvas id="dashboardFinanceChart" height="180"></canvas>
                </div>

                {{-- Recent Transactions --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant">
                        <h3 class="text-label-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-base">receipt_long</span>
                            {{ __('messages.recent_transactions') }}
                        </h3>
                    </div>
                    <ul class="divide-y divide-outline-variant">
                        @forelse ($recent_finance as $tx)
                            <li class="flex items-center gap-3 px-5 py-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                    {{ $tx->type === 'income' ? 'bg-success/10' : 'bg-error/10' }}">
                                    <span class="material-symbols-outlined text-sm
                                        {{ $tx->type === 'income' ? 'text-success' : 'text-error' }}">
                                        {{ $tx->type === 'income' ? 'arrow_downward' : 'arrow_upward' }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-label-sm font-semibold text-on-surface truncate">
                                        {{ $tx->description ?: ($tx->category->name ?? '—') }}
                                    </p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $tx->transaction_date_formatted }}</p>
                                </div>
                                <p class="text-label-sm font-bold shrink-0 {{ $tx->type === 'income' ? 'text-success' : 'text-error' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format((float) $tx->amount, 0, '.', ',') }}
                                </p>
                            </li>
                        @empty
                            <li class="px-5 py-6 text-center text-on-surface-variant text-label-sm">{{ __('messages.no_data') }}</li>
                        @endforelse
                    </ul>
                </div>

            </div>

        </div>

    @endif

    {{-- ══════════════════════ QUICK ACTIONS ══════════════════════ --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-outline-variant">
        <h3 class="text-label-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-base">bolt</span>
            {{ __('messages.quick_actions') }}
        </h3>
        <div class="flex flex-wrap gap-3">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('personnel.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-xl text-label-sm font-bold hover:bg-primary-container transition-all btn-press shadow-sm">
                    <span class="material-symbols-outlined text-base">person_add</span>
                    {{ __('messages.new_entry') }}
                </a>
            @endif
            @if (auth()->user()->canManageNews())
                <a href="{{ route('news.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-xl text-label-sm font-bold hover:opacity-90 transition-all btn-press shadow-sm">
                    <span class="material-symbols-outlined text-base">add_circle</span>
                    {{ __('messages.news_add') }}
                </a>
            @endif
            @if (auth()->user()->canManageDocuments())
                <a href="{{ route('documents.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-tertiary text-white rounded-xl text-label-sm font-bold hover:opacity-90 transition-all btn-press shadow-sm">
                    <span class="material-symbols-outlined text-base">upload_file</span>
                    {{ __('messages.upload_document') }}
                </a>
            @endif
            @if (auth()->user()->canManageFinance())
                <a href="{{ route('finance.transactions.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-success text-black rounded-xl text-label-sm font-bold hover:opacity-90 transition-all btn-press shadow-sm">
                    <span class="material-symbols-outlined text-base">add_card</span>
                    {{ __('messages.add_transaction') }}
                </a>
                <a href="{{ route('finance.report') }}"
                   class="flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-xl text-label-sm font-bold hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-base">summarize</span>
                    {{ __('messages.finance_report') }}
                </a>
            @endif
            @if (auth()->user()->isSuperAdmin())
                <a href="{{ route('settings') }}"
                   class="flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-xl text-label-sm font-bold hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-base">settings</span>
                    {{ __('messages.settings') }}
                </a>
            @endif
        </div>
    </div>

</div>

{{-- Finance Chart Script --}}
@if (auth()->user()->canManageFinance())
@push('scripts')
<script>
    (function () {
        const ctx = document.getElementById('dashboardFinanceChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chart_labels),
                datasets: [
                    {
                        label: '{{ __("messages.income") }}',
                        data: @json($chart_income),
                        backgroundColor: 'rgba(34,197,94,0.7)',
                        borderRadius: 6,
                    },
                    {
                        label: '{{ __("messages.expense") }}',
                        data: @json($chart_expense),
                        backgroundColor: 'rgba(239,68,68,0.7)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 },
                            callback: v => v.toLocaleString()
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    })();
</script>
@endpush
@endif
