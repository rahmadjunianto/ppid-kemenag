<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->information->title }} - PPID Kementerian Agama</title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <main class="max-w-4xl mx-auto px-4 py-10">
        <!-- Tombol Kembali -->
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-800 mb-6 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 transition">
            &larr; Kembali ke Daftar Informasi
        </a>

        <article class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6 sm:p-10">
            <div class="border-b border-slate-100 pb-6 mb-6">
                @if($item->information->parent)
                <span
                    class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                    {{ $item->information->parent->code }}. {{ $item->information->parent->title }}
                </span>
                @endif

                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 mt-3 leading-snug">
                    {{ $item->information->title }}
                </h1>

                <!-- META INFO POSTINGAN -->
                <div
                    class="post-single-konten flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-4 pt-3 border-t border-slate-100">
                    <span><i class="fas fa-user text-emerald-600 mr-1"></i> Admin</span> |
                    <span><i class="fas fa-calendar-alt text-emerald-600 mr-1"></i> {{
                        $item->updated_at->translatedFormat('d F Y') }}</span> |
                    <span><i class="fas fa-eye text-emerald-600 mr-1"></i> Dibaca {{ $item->download_count }}
                        kali</span>
                </div>
            </div>

            <!-- Isi Konten Artikel (Rich Text dari Filament) -->
            <div class="prose prose-emerald max-w-none text-sm sm:text-base leading-relaxed">
                {!! $item->content !!}
            </div>
        </article>
        @if(!empty($item->map_embed_url))
        <div class="mt-8">
            <h3 class="text-sm font-bold text-slate-700 mb-3"><i
                    class="fas fa-map-marker-alt text-emerald-600 mr-1"></i> Lokasi Peta</h3>
            <div class="w-full h-80 rounded-xl overflow-hidden border border-slate-200 shadow-xs">
                <iframe src="{{ $item->map_embed_url }}" class="w-full h-full border-0" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        @endif
    </main>

</body>

</html>