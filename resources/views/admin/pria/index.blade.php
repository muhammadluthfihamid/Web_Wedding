@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ Auth::user()->isKhitanan() ? 'Data Anak Khitan 👦' : 'Biodata Pengantin Pria 💍' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ Auth::user()->isKhitanan() ? 'Kelola data profil ananda yang dikhitan' : 'Kelola data profil lengkap pengantin pria' }}
            </p>
        </div>
        @if ($biodataPria->isEmpty())
            <div class="flex-shrink-0">
                <a class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
                   href="{{ route('biodataPria.create') }}" title="Tambah Biodata" role="button">
                    <i class="fas fa-plus"></i> {{ Auth::user()->isKhitanan() ? 'Tambah Data Anak' : 'Tambah Biodata' }}
                </a>
            </div>
        @endif
    </div>

    @if ($biodataPria->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center space-y-4">
            <div class="inline-flex items-center justify-center p-4 bg-indigo-50 text-indigo-600 rounded-full shadow-inner">
                <i class="fas {{ Auth::user()->isKhitanan() ? 'fa-child' : 'fa-user-circle' }} text-4xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-950">
                    {{ Auth::user()->isKhitanan() ? 'Belum Ada Data Anak' : 'Belum Ada Biodata Pria' }}
                </h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto">
                    {{ Auth::user()->isKhitanan() ? 'Tambahkan informasi ananda yang dikhitan, nama orang tua, dan foto profil.' : 'Tambahkan informasi nama pengantin pria, orang tua, deskripsi singkat, dan foto.' }}
                </p>
            </div>
            <a class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" 
               href="{{ route('biodataPria.create') }}">
                <i class="fas fa-plus"></i> {{ Auth::user()->isKhitanan() ? 'Tambah Data Anak' : 'Tambah Biodata Pria' }}
            </a>
        @else
            @foreach ($biodataPria as $pria)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8 max-w-3xl">
                    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ Auth::user()->isKhitanan() ? 'Profil Ananda Khitan' : 'Profil Pengantin Pria' }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('biodataPria.edit', $pria) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-edit"></i> Edit Profil
                            </a>
                            <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-sm font-semibold transition-colors" 
                                    data-id="{{ $pria->id }}"
                                    onclick="confirmDelete(this.dataset.id)">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <form id="delete-form-{{ $pria->id }}" action="{{ route('biodataPria.destroy', $pria) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col sm:flex-row gap-8 items-start">
                        <!-- Foto Profil -->
                        <div class="w-full sm:w-1/3 flex flex-col items-center">
                            @if($pria->foto)
                                <img src="{{ asset('storage/' . $pria->foto) }}" alt="Foto Pria" class="w-48 h-48 rounded-2xl object-cover border-4 border-slate-50 shadow-md">
                            @else
                                <div class="w-48 h-48 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-4xl border border-slate-100 shadow-inner">
                                    {{ strtoupper(substr($pria->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Data Informasi -->
                        <div class="flex-1 space-y-4 w-full">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Nama Lengkap:</span>
                                <span class="text-xl font-bold text-slate-900 block mt-0.5">{{ $pria->nama }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-50">
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold block">Nama Ayah (Bapak):</span>
                                    <span class="text-sm font-bold text-slate-800 block mt-0.5">{{ $pria->bapak }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 font-semibold block">Nama Ibu:</span>
                                    <span class="text-sm font-bold text-slate-800 block mt-0.5">{{ $pria->ibu }}</span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-50">
                                <span class="text-xs text-slate-400 font-semibold block">Asal / Lokasi:</span>
                                <span class="text-sm font-bold text-slate-800 block mt-0.5">{{ $pria->asal ?? '-' }}</span>
                            </div>

                            @if($pria->deskripsi)
                                <div class="pt-2 border-t border-slate-50">
                                    <span class="text-xs text-slate-400 font-semibold block">Deskripsi Singkat:</span>
                                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $pria->deskripsi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data profil pengantin pria ini akan dihapus secara permanen!",
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
