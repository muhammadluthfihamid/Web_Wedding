@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kado Pernikahan (Gifts)</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola nomor rekening bank atau e-wallet untuk hadiah digital</p>
        </div>
        <div class="flex-shrink-0">
            <a class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
               href="{{ route('gifts.create') }}" title="Tambah Rekening" role="button">
                <i class="fas fa-plus"></i> Tambah Rekening
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-[5%]">No.</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Penerima</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Bank / E-Wallet</th>
                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Warna Kartu</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Rekening</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-[15%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @if ($gifts->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <div class="text-slate-400 text-sm">Belum ada data rekening hadiah yang tersedia.</div>
                            </td>
                        </tr>
                    @else
                        @foreach ($gifts as $gift)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $gift->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">{{ $gift->nama_bank }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono font-bold border shadow-sm"
                                         style="background-color: <?= $gift->bg_color ?? '#3e4b6d' ?>; color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                        <span class="w-2.5 h-2.5 rounded-full bg-white/80"></span>
                                        {{ strtoupper($gift->bg_color ?? '#3e4b6d') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800 font-mono font-bold">{{ $gift->no_rek }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-normal break-words max-w-[200px]">{{ Str::limit($gift->deskripsi, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('gifts.edit', $gift) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold transition-colors">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors" 
                                                data-id="{{ $gift->id }}"
                                                onclick="confirmDelete(this.dataset.id)">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $gift->id }}" action="{{ route('gifts.destroy', $gift) }}"
                                        method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data rekening hadiah ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
