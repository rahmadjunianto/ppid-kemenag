@extends('layouts.app')

@section('title', ($pageTitle ?? 'Informasi Publik') . ' - PPID Kementerian Agama')

@section('content')
<div x-data="{
    search: '',
    activeTab: 'all',
    modalOpen: false,
    pdfUrl: '',
    pdfTitle: '',
    openPdf(url, title) {
        this.pdfUrl = url;
        this.pdfTitle = title;
        this.modalOpen = true;
    }
}">

    <!-- Sub-Header Navigasi Kategori (Berkala, Serta Merta, dll) -->
    <header class="bg-emerald-800 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-base font-bold tracking-wide">PPID KEMENTERIAN AGAMA</h1>
                <p class="text-[11px] text-emerald-200">Pejabat Pengelola Informasi dan Dokumentasi</p>
            </div>
            <nav class="flex flex-wrap gap-1.5 text-xs font-medium">
                <a href="{{ route('ppid.index', 'informasi-berkala') }}"
                   class="px-3 py-1 rounded-md transition {{ ($currentSlug ?? '') === 'informasi-berkala' ? 'bg-white text-emerald-900 font-bold' : 'bg-emerald-700/60 hover:bg-emerald-700' }}">
                    Berkala
                </a>
                <a href="{{ route('ppid.index', 'informasi-serta-merta') }}"
                   class="px-3 py-1 rounded-md transition {{ ($currentSlug ?? '') === 'informasi-serta-merta' ? 'bg-white text-emerald-900 font-bold' : 'bg-emerald-700/60 hover:bg-emerald-700' }}">
                    Serta Merta
                </a>
                <a href="{{ route('ppid.index', 'informasi-setiap-saat') }}"
                   class="px-3 py-1 rounded-md transition {{ ($currentSlug ?? '') === 'informasi-setiap-saat' ? 'bg-white text-emerald-900 font-bold' : 'bg-emerald-700/60 hover:bg-emerald-700' }}">
                    Setiap Saat
                </a>
                <a href="{{ route('ppid.index', 'informasi-dikecualikan') }}"
                   class="px-3 py-1 rounded-md transition {{ ($currentSlug ?? '') === 'informasi-dikecualikan' ? 'bg-white text-emerald-900 font-bold' : 'bg-emerald-700/60 hover:bg-emerald-700' }}">
                    Dikecualikan
                </a>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <div class="max-w-6xl mx-auto px-4 py-6">

        <!-- Header & Search Bar -->
        <div class="bg-white rounded-xl p-5 shadow-xs border border-slate-200 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $pageTitle ?? '' }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $pageSubtitle ?? '' }}</p>
            </div>
            <div class="w-full md:w-80">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Cepat cari dokumen / sub-menu..."
                           class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Filter Kategori (Tabs) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-3 mb-4 scrollbar-none">
            <button @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200 shadow-2xs whitespace-nowrap cursor-pointer transition">
                Semua Kategori
            </button>
            @foreach($categories as $cat)
                <button @click="activeTab = '{{ $cat->code ?? $loop->index }}'"
                        :class="activeTab === '{{ $cat->code ?? $loop->index }}' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-700 hover:bg-slate-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border border-slate-200 shadow-2xs whitespace-nowrap cursor-pointer transition">
                    {{ $cat->code ? $cat->code . '. ' : '' }}{{ Str::limit($cat->title, 25) }}
                </button>
            @endforeach
        </div>

        <!-- Daftar Kategori (Accordion Compact) -->
        <div class="space-y-4">
            @forelse($categories as $category)
                <div x-show="activeTab === 'all' || activeTab === '{{ $category->code ?? $loop->index }}'"
                     x-data="{ open: false }"
                     class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden transition">

                    <!-- Header Kategori -->
                    <button @click="open = !open" type="button"
                            class="w-full px-5 py-3.5 bg-white hover:bg-slate-50 text-slate-800 flex items-center justify-between text-left transition cursor-pointer border-l-4 border-emerald-700">
                        <div class="flex items-center gap-3">
                            @if($category->code)
                                <span class="w-7 h-7 rounded-md bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $category->code }}
                                </span>
                            @endif
                            <div>
                                <h3 class="font-bold text-sm text-slate-900">{{ $category->title }}</h3>
                                <p class="text-[11px] text-slate-500 font-normal">{{ $category->children->count() }} Sub-Informasi tersedia</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md" x-text="open ? 'Tutup' : 'Buka Detail'"></span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <!-- Isi Sub-Menu -->
                    <div x-show="open" x-transition class="p-3 bg-slate-50/70 border-t border-slate-100 space-y-1.5">
                        @foreach($category->children as $subItem)
                            <div x-show="search === '' || '{{ strtolower($subItem->title) }}'.includes(search.toLowerCase())"
                                 class="bg-white px-3.5 py-2.5 rounded-lg border border-slate-200/80 shadow-2xs flex items-center justify-between gap-4 hover:bg-slate-50 transition">

                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if($subItem->code)
                                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded shrink-0">
                                            {{ $category->code ? $category->code . '.' : '' }}{{ $subItem->code }}
                                        </span>
                                    @endif
                                    <h4 class="text-xs font-semibold text-slate-800 truncate">
                                        {{ $subItem->title }}
                                    </h4>
                                </div>

                                @if($subItem->items->isNotEmpty())
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        @foreach($subItem->items as $doc)
                                            @php
                                                $isLocalPdf = $doc->action_type === 'file' && !empty($doc->file_path);

                                                if ($doc->action_type === 'internal' && !empty($doc->internal_url)) {
                                                    $targetUrl = url($doc->internal_url);
                                                } else {
                                                    $targetUrl = $doc->target_url ?? '#';
                                                }

                                                $isInternal = $doc->action_type === 'content' ||
                                                              $doc->action_type === 'internal' ||
                                                              Str::startsWith($targetUrl, url('/')) ||
                                                              Str::startsWith($targetUrl, '/');
                                            @endphp

                                            @if($isLocalPdf)
                                                <button @click="openPdf('{{ $targetUrl }}', '{{ $subItem->title }} - {{ $doc->label }}')"
                                                        class="px-2.5 py-1 rounded text-[11px] font-medium bg-emerald-600 hover:bg-emerald-700 text-white transition cursor-pointer shadow-2xs whitespace-nowrap">
                                                    {{ $doc->label }}
                                                </button>
                                            @else
                                                <a href="{{ $targetUrl }}"
                                                   target="{{ $isInternal ? '_self' : '_blank' }}"
                                                   class="px-2.5 py-1 rounded text-[11px] font-medium bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition whitespace-nowrap">
                                                    {{ $doc->label }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl p-10 text-center border border-slate-200">
                    <p class="text-slate-500 text-xs">Belum ada data {{ $pageTitle ?? 'dokumen' }} yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

    </div>

    <!-- MODAL POP-UP PREVIEW PDF -->
    <div x-show="modalOpen"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6"
         x-transition.opacity
         style="display: none;">

        <div @click.away="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-5 py-3 bg-emerald-800 text-white flex items-center justify-between">
                <h3 class="font-bold text-sm truncate pr-4" x-text="pdfTitle"></h3>
                <button @click="modalOpen = false" class="text-emerald-200 hover:text-white p-1 rounded-lg hover:bg-emerald-700 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-3 bg-slate-100 flex-1 flex items-center justify-center min-h-[450px]">
                <template x-if="modalOpen">
                    <iframe :src="pdfUrl" class="w-full h-[65vh] rounded-lg border border-slate-300 bg-white"></iframe>
                </template>
            </div>
            <div class="px-5 py-2.5 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-[11px] text-slate-500">Pratinjau Dokumen Resmi PPID Kemenag</span>
                <div class="flex gap-2">
                    <a :href="pdfUrl" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold rounded-lg transition">
                        Buka di Tab Baru
                    </a>
                    <button @click="modalOpen = false" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-[11px] font-semibold rounded-lg transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection