<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LHKPN Pejabat Tahun {{ $selectedYear }} - PPID Kementerian Agama</title>
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
        <!-- Tombol Kembali -->
        <a href="{{ route('ppid.index', 'informasi-berkala') }}"
           class="inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800 mb-6">
            &larr; Kembali ke Informasi Berkala
        </a>

        <!-- Header & Pilihan Tahun -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded">
                        A.6 Informasi Berkala
                    </span>
                    <h1 class="text-2xl font-bold text-slate-900 mt-2">
                        Laporan Harta Kekayaan Penyelenggara Negara (LHKPN)
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilih tahun pelaporan untuk melihat daftar LHKPN Pejabat Struktural.
                    </p>
                </div>

                <!-- Deretan Tombol Filter Tahun -->
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($availableYears as $year)
                        <a href="{{ route('ppid.lhkpn', ['year' => $year]) }}"
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

        <!-- Tabel Daftar Pejabat -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-emerald-800 text-white flex items-center justify-between">
                <h2 class="font-semibold">Daftar LHKPN Pejabat — Tahun Pelaporan {{ $selectedYear }}</h2>
                <span class="text-xs bg-emerald-900/60 px-3 py-1 rounded-full">
                    {{ $lhkpns->count() }} Pejabat
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-xs uppercase text-slate-600 font-bold">
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama</th>
                            <th class="py-3.5 px-4">NIP</th>
                            <th class="py-3.5 px-4">Jabatan</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse($lhkpns as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-4 px-4 text-center font-medium text-slate-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-900">
                                    {{ $item->official->name }}
                                </td>
                                <td class="py-4 px-4 font-mono text-slate-600">
                                    {{ $item->official->nip ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-slate-700">
                                    {{ $item->position_at_year ?: $item->official->position }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($item->file_path)
                                        <!-- Jika ada file PDF lokal, buka di dalam Modal Pop-up -->
                                        <button @click="openPdf('{{ Storage::url($item->file_path) }}', 'LHKPN {{ $item->year }} - {{ $item->official->name }}')"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Lihat LHKPN</span>
                                        </button>
                                    @else
                                        <!-- Jika link eksternal (KPK), buka di tab baru -->
                                        <a href="{{ $item->external_url ?? '#' }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-600 hover:bg-slate-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                            <span>Portal e-LHKPN</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                    Belum ada data laporan LHKPN untuk tahun {{ $selectedYear }}.
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

            <!-- Modal Header -->
            <div class="px-6 py-4 bg-emerald-800 text-white flex items-center justify-between">
                <h3 class="font-bold text-base truncate" x-text="pdfTitle"></h3>
                <button @click="modalOpen = false"
                        class="text-emerald-200 hover:text-white p-1 rounded-lg hover:bg-emerald-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body (PDF Viewer Frame) -->
            <div class="p-4 bg-slate-100 flex-1 flex items-center justify-center min-h-[500px]">
                <template x-if="modalOpen">
                    <iframe :src="pdfUrl" class="w-full h-[70vh] rounded-lg border border-slate-300 bg-white"></iframe>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                <a :href="pdfUrl" target="_blank"
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                    Buka di Tab Baru
                </a>
                <button @click="modalOpen = false"
                        class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</body>
</html>