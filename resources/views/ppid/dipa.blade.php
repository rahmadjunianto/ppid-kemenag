<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIPA Tahun {{ $selectedYear }} - PPID Kementerian Agama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased"
      x-data="{
          modalOpen: false,
          pdfUrl: '',
          pdfTitle: '',
          openPdf(url, title) {
              this.pdfUrl = url;
              this.pdfTitle = title;
              this.modalOpen = true;
          }
      }">

    <main class="max-w-5xl mx-auto px-4 py-10">
        <a href="{{ route('ppid.index', 'informasi-berkala') }}"
           class="inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800 mb-6">
            &larr; Kembali ke Informasi Berkala
        </a>

        <!-- Header & Pilihan Tahun -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded">
                        B.5 Informasi Berkala
                    </span>
                    <h1 class="text-2xl font-bold text-slate-900 mt-2">
                        Anggaran Program & Kegiatan (Ringkasan DIPA)
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilih tahun anggaran untuk melihat dokumen DIPA per Bidang / Unit Kerja.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @foreach($availableYears as $year)
                        <a href="{{ route('ppid.dipa', ['year' => $year]) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold border transition
                                  {{ $selectedYear === (int) $year
                                      ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm'
                                      : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-100' }}">
                            Tahun {{ $year }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Bidang -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-emerald-800 text-white flex items-center justify-between">
                <h2 class="font-semibold">Daftar DIPA Unit Kerja — Tahun Anggaran {{ $selectedYear }}</h2>
                <span class="text-xs bg-emerald-900/60 px-3 py-1 rounded-full">
                    {{ $documents->count() }} Unit / Bidang
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-xs uppercase text-slate-600 font-bold">
                            <th class="py-3.5 px-4 w-16 text-center">Kode</th>
                            <th class="py-3.5 px-4">Nama Bidang / Bagian / Unit Kerja</th>
                            <th class="py-3.5 px-4">Keterangan</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse($documents as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-4 text-center font-mono font-bold text-slate-500">
                                    {{ $item->code ?? $loop->iteration }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-900">
                                    {{ $item->division_name }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 text-xs">
                                    {{ $item->description ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($item->file_path)
                                        <button @click="openPdf('{{ Storage::url($item->file_path) }}', 'DIPA {{ $item->year }} - {{ $item->division_name }}')"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition cursor-pointer">
                                            <span>Lihat DIPA</span>
                                        </button>
                                    @else
                                        <a href="{{ $item->external_url ?? '#' }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-600 hover:bg-slate-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                            <span>Unduh / Lihat</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 italic">
                                    Belum ada data DIPA untuk tahun {{ $selectedYear }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL POP-UP PREVIEW PDF -->
    <div x-show="modalOpen"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6"
         x-transition.opacity
         style="display: none;">

        <div @click.away="modalOpen = false"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 bg-emerald-800 text-white flex items-center justify-between">
                <h3 class="font-bold text-base truncate pr-4" x-text="pdfTitle"></h3>
                <button @click="modalOpen = false" class="text-emerald-200 hover:text-white p-1 rounded-lg hover:bg-emerald-700 transition cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 bg-slate-100 flex-1 flex items-center justify-center min-h-[500px]">
                <template x-if="modalOpen">
                    <iframe :src="pdfUrl" class="w-full h-[70vh] rounded-lg border border-slate-300 bg-white"></iframe>
                </template>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <span class="text-xs text-slate-500">Pratinjau DIPA Resmi PPID Kemenag</span>
                <div class="flex gap-3">
                    <a :href="pdfUrl" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                        Buka di Tab Baru
                    </a>
                    <button @click="modalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>