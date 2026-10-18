<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->title }} - PPID Kementerian Agama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <main class="max-w-5xl mx-auto px-4 py-10">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800 mb-6">
            &larr; Kembali
        </a>

        <!-- Header -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mb-6">
            <h1 class="text-2xl font-bold text-slate-900">{{ $category->title }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $category->description ?? 'Daftar rincian informasi publik.' }}</p>
        </div>

        <!-- Tabel Detail -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-xs uppercase text-slate-600 font-bold">
                            <th class="py-3.5 px-4 w-16 text-center">No</th>
                            <th class="py-3.5 px-4">Nama / Judul Informasi</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi Detil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse($children as $child)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-4 text-center font-mono text-slate-500">
                                    {{ $child->code ?? $loop->iteration }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-900">
                                    {{ $child->title }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @foreach($child->items as $doc)
                                        <a href="{{ $doc->target_url }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                            <span>Lihat Detil</span>
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400 italic">
                                    Belum ada rincian data untuk kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>