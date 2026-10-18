@php
    // Ambil menu utama yang aktif beserta submenu di dalamnya, diurutkan berdasarkan sort_order
    $navMenus = \App\Models\Menu::where('is_active', true)
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->with(['children' => function($query) {
            $query->where('is_active', true)->orderBy('sort_order');
        }])
        ->get();
@endphp

<!-- NAVBAR UTAMA PPID -->
<header class="bg-emerald-900 text-white shadow-md sticky top-0 z-40" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <!-- Logo & Judul Instansi -->
            <div class="flex items-center gap-3">
                <div class="bg-white/10 p-2 rounded-xl">
                    <i class="fas fa-balance-scale text-emerald-300 text-2xl"></i>
                </div>
                <div>
                    <a href="{{ url('/') }}" class="font-bold text-base sm:text-lg tracking-wide hover:text-emerald-200 transition">
                        PPID KEMENTERIAN AGAMA
                    </a>
                    <p class="text-[11px] text-emerald-200/80">Layanan Informasi Publik Terpadu</p>
                </div>
            </div>

            <!-- Desktop Navigation Menu (Dinamis dari Database) -->
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($navMenus as $menu)
                    @if($menu->children->isNotEmpty())
                        <!-- MENU DENGAN SUBMENU (DROPDOWN) -->
                        <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                            <button @click="open = !open"
                                    @mouseenter="open = true"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-800 transition cursor-pointer">
                                <span>{{ $menu->label }}</span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open"
                                 x-transition.origin.top.duration.150ms
                                 class="absolute left-0 mt-1 w-56 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-200 py-2 z-50"
                                 style="display: none;">
                                @foreach($menu->children as $child)
                                    <a href="{{ url($child->url) }}"
                                       class="block px-4 py-2 text-xs sm:text-sm hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- MENU BIASA TANPA SUBMENU -->
                        <a href="{{ url($menu->url) }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-800 transition">
                            {{ $menu->label }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <!-- Tombol Hamburger Menu Mobile -->
            <div class="flex lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded-lg bg-emerald-800 text-emerald-200 hover:text-white focus:outline-none transition">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen"
         x-transition.origin.top
         class="lg:hidden bg-emerald-950 border-t border-emerald-800 px-4 pt-3 pb-6 space-y-2"
         style="display: none;">
        @foreach($navMenus as $menu)
            @if($menu->children->isNotEmpty())
                <div x-data="{ subOpen: false }" class="space-y-1">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-900 transition">
                        <span>{{ $menu->label }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="subOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="subOpen" class="pl-4 space-y-1 border-l-2 border-emerald-800 ml-3 py-1">
                        @foreach($menu->children as $child)
                            <a href="{{ url($child->url) }}" class="block px-3 py-1.5 text-xs hover:text-emerald-300 transition">
                                {{ $child->label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ url($menu->url) }}" class="block px-3 py-2 rounded-lg text-sm font-medium hover:bg-emerald-900 transition">
                    {{ $menu->label }}
                </a>
            @endif
        @endforeach
    </div>
</header>