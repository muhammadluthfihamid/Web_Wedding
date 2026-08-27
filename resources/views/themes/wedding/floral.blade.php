<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('components.og-meta')
    @php
        $istri = isset($biodataWanita) && $biodataWanita->isNotEmpty() ? $biodataWanita->first() : null;
        $pria = isset($biodataPria) && $biodataPria->isNotEmpty() ? $biodataPria->first() : null;
        $namaIstri = $istri ? $istri->nama : 'Mempelai Wanita';
        $namaPria = $pria ? $pria->nama : 'Mempelai Pria';
        $coupleTitle = $namaPria . ' & ' . $namaIstri;
        $firstInfo = isset($infos) && $infos->isNotEmpty() ? $infos->first() : null;
        $guestTo = request('to') ?: null;
    @endphp

    <title>Undangan Pernikahan {{ $coupleTitle }} | {{ $store_name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .font-serif-garamond { font-family: 'Cormorant Garamond', serif; }
        .font-script-vibes { font-family: 'Great Vibes', cursive; }
        .bg-floral {
            background-color: #fffdfa;
            background-image: radial-gradient(#fbcfe8 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body class="bg-floral text-slate-800 min-h-screen">

    {{-- Hero Section --}}
    <section class="min-h-screen flex flex-col justify-between p-6 text-center relative overflow-hidden bg-gradient-to-b from-rose-100/80 to-pink-50">
        <div class="pt-8">
            <span class="inline-block px-5 py-2 bg-white/80 backdrop-blur-md border border-rose-200 rounded-full text-xs font-semibold tracking-widest text-rose-700 uppercase">
                The Wedding of
            </span>
        </div>

        <div class="py-12" data-aos="zoom-in">
            <h1 class="text-5xl md:text-7xl font-script-vibes text-rose-800 my-4 drop-shadow-sm">{{ $coupleTitle }}</h1>
            <p class="text-slate-600 text-sm md:text-base font-serif-garamond italic">"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari meksudmu sendiri."</p>
        </div>

        @if($guestTo)
        <div class="bg-white/90 border border-rose-200 rounded-2xl p-4 max-w-md mx-auto mb-8 shadow-sm">
            <p class="text-xs text-rose-500 font-medium">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <p class="text-lg font-bold text-slate-900 mt-1">{{ $guestTo }}</p>
        </div>
        @endif

        <div class="pb-8">
            <a href="#mempelai" class="inline-flex items-center gap-2 px-8 py-3.5 bg-rose-600 text-white rounded-full font-semibold text-sm shadow-md hover:bg-rose-700 transition-all">
                <i class="fas fa-heart"></i> Buka Undangan
            </a>
        </div>
    </section>

    {{-- Mempelai Section --}}
    <section id="mempelai" class="py-16 px-6 max-w-4xl mx-auto text-center" data-aos="fade-up">
        <h2 class="text-4xl font-serif-garamond font-bold text-rose-900 mb-12">Pasangan Mempelai</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            {{-- Pria --}}
            <div class="bg-white border border-rose-100 p-8 rounded-3xl shadow-sm">
                <div class="w-32 h-32 mx-auto rounded-full p-1 bg-rose-200 mb-4 overflow-hidden shadow-inner">
                    <img src="{{ $pria && $pria->foto ? asset('storage/'.$pria->foto) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $namaPria }}" class="w-full h-full object-cover rounded-full">
                </div>
                <h3 class="text-2xl font-serif-garamond font-bold text-slate-900">{{ $pria->nama_lengkap ?? $namaPria }}</h3>
                <p class="text-xs text-rose-600 font-semibold uppercase mt-1">Mempelai Pria</p>
                <p class="text-xs text-slate-500 mt-3">Putra dari Bpk. {{ $pria->nama_ayah ?? '...' }} & Ibu {{ $pria->nama_ibu ?? '...' }}</p>
            </div>

            {{-- Wanita --}}
            <div class="bg-white border border-rose-100 p-8 rounded-3xl shadow-sm">
                <div class="w-32 h-32 mx-auto rounded-full p-1 bg-rose-200 mb-4 overflow-hidden shadow-inner">
                    <img src="{{ $istri && $istri->foto ? asset('storage/'.$istri->foto) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $namaIstri }}" class="w-full h-full object-cover rounded-full">
                </div>
                <h3 class="text-2xl font-serif-garamond font-bold text-slate-900">{{ $istri->nama_lengkap ?? $namaIstri }}</h3>
                <p class="text-xs text-rose-600 font-semibold uppercase mt-1">Mempelai Wanita</p>
                <p class="text-xs text-slate-500 mt-3">Putri dari Bpk. {{ $istri->nama_ayah ?? '...' }} & Ibu {{ $istri->nama_ibu ?? '...' }}</p>
            </div>
        </div>
    </section>

    {{-- Tanggal & Acara --}}
    @if($firstInfo)
    <section class="py-16 px-6 bg-rose-50/60 border-y border-rose-100">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-4xl font-serif-garamond font-bold text-rose-900 mb-10">Waktu & Tempat Pernikahan</h2>

            @php
                $hasAcara3 = !empty($firstInfo->mulai_acara_3);
                $gridCols = $hasAcara3 ? 'grid-cols-1 md:grid-cols-3' : 'grid-cols-1 md:grid-cols-2';
            @endphp
            <div class="grid {{ $gridCols }} gap-8">
                <div class="bg-white border border-rose-200/60 p-6 rounded-2xl text-left shadow-sm">
                    <h3 class="font-serif-garamond text-xl font-bold text-rose-800 mb-2">Akad Nikah</h3>
                    <p class="text-xs text-slate-500 mb-3"><i class="far fa-calendar text-rose-500 mr-2"></i> {{ $firstInfo->tanggal_pernikahan ? \Carbon\Carbon::parse($firstInfo->tanggal_pernikahan)->translatedFormat('l, d F Y') : '-' }}</p>
                    <p class="text-sm text-slate-700 mb-2"><i class="far fa-clock text-rose-500 mr-2"></i> {{ $firstInfo->mulai_akad ? \Carbon\Carbon::parse($firstInfo->mulai_akad)->format('H.i') : '08.00' }} - {{ $firstInfo->selesai_akad ? \Carbon\Carbon::parse($firstInfo->selesai_akad)->format('H.i') : 'Selesai' }} WIB</p>
                    <p class="text-sm text-slate-700"><i class="fas fa-map-marker-alt text-rose-500 mr-2"></i> {{ $firstInfo->alamat ?? '-' }}</p>
                </div>

                <div class="bg-white border border-rose-200/60 p-6 rounded-2xl text-left shadow-sm">
                    <h3 class="font-serif-garamond text-xl font-bold text-rose-800 mb-2">Resepsi Pernikahan</h3>
                    <p class="text-xs text-slate-500 mb-3"><i class="far fa-calendar text-rose-500 mr-2"></i> {{ $firstInfo->tanggal_pernikahan ? \Carbon\Carbon::parse($firstInfo->tanggal_pernikahan)->translatedFormat('l, d F Y') : '-' }}</p>
                    <p class="text-sm text-slate-700 mb-2"><i class="far fa-clock text-rose-500 mr-2"></i> {{ $firstInfo->mulai_resepsi ? \Carbon\Carbon::parse($firstInfo->mulai_resepsi)->format('H.i') : '11.00' }} WIB - Selesai</p>
                    <p class="text-sm text-slate-700"><i class="fas fa-map-marker-alt text-rose-500 mr-2"></i> {{ $firstInfo->alamat ?? '-' }}</p>
                </div>

                @if($hasAcara3)
                <div class="bg-white border border-rose-200/60 p-6 rounded-2xl text-left shadow-sm">
                    <h3 class="font-serif-garamond text-xl font-bold text-rose-800 mb-2">{{ $firstInfo->nama_acara_3 ?: 'Resepsi Malam' }}</h3>
                    <p class="text-xs text-slate-500 mb-3"><i class="far fa-calendar text-rose-500 mr-2"></i> {{ $firstInfo->tanggal_pernikahan ? \Carbon\Carbon::parse($firstInfo->tanggal_pernikahan)->translatedFormat('l, d F Y') : '-' }}</p>
                    <p class="text-sm text-slate-700 mb-2"><i class="fas fa-moon text-rose-500 mr-2"></i> {{ \Carbon\Carbon::parse($firstInfo->mulai_acara_3)->format('H.i') }} WIB {{ $firstInfo->selesai_acara_3 ? '- ' . \Carbon\Carbon::parse($firstInfo->selesai_acara_3)->format('H.i') . ' WIB' : '- Selesai' }}</p>
                    <p class="text-sm text-slate-700"><i class="fas fa-map-marker-alt text-rose-500 mr-2"></i> {{ $firstInfo->alamat ?? '-' }}</p>
                </div>
                @endif
            </div>

            @if($firstInfo->deskripsi)
            <div class="mt-8 text-xs text-slate-600 max-w-2xl mx-auto leading-relaxed" style="white-space: pre-line;">
                {!! nl2br(e($firstInfo->deskripsi)) !!}
            </div>
            @endif

            @if($firstInfo->link_maps)
            <div class="mt-8">
                <a href="{{ $firstInfo->link_maps }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-600 text-white font-semibold rounded-xl text-sm shadow hover:bg-rose-700 transition-all">
                    <i class="fas fa-map-marked-alt"></i> Petunjuk Lokasi Maps
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Gift Section --}}
    @if(isset($gifts) && $gifts->isNotEmpty())
    <section class="py-16 px-6 max-w-3xl mx-auto text-center" data-aos="fade-up">
        <h2 class="text-4xl font-serif-garamond font-bold text-rose-900 mb-3">Wedding Gift</h2>
        <p class="text-slate-500 text-sm mb-8">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Apabila Anda ingin memberi hadiah pasangan:</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($gifts as $gift)
            <div class="bg-white border border-rose-200 p-6 rounded-2xl shadow-sm">
                <p class="text-xs text-rose-600 font-bold uppercase tracking-wider mb-1">{{ $gift->nama_bank }}</p>
                <p class="text-2xl font-bold text-slate-900 tracking-wider my-2">{{ $gift->no_rekening }}</p>
                <p class="text-sm text-slate-600">a/n {{ $gift->atas_nama }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Wishes Section --}}
    <section class="py-16 px-6 max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-serif-garamond font-bold text-rose-900 mb-2">Ucapan & Doa Restu</h2>
            <p class="text-slate-500 text-sm">Tulis ucapan bahagia Anda untuk kedua mempelai.</p>
        </div>

        <form action="{{ route('wish.storePublic') }}" method="POST" class="bg-white border border-rose-200 p-6 rounded-2xl shadow-sm space-y-4 mb-8">
            @csrf
            <div>
                <input type="text" name="nama" placeholder="Nama Anda" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-rose-400">
            </div>
            <div>
                <textarea name="ucapan" rows="3" placeholder="Tulis pesan ucapan Anda..." required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-rose-400"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-rose-600 text-white font-semibold rounded-xl text-sm hover:bg-rose-700 transition-colors">
                <i class="fas fa-paper-plane mr-1"></i> Kirim Ucapan
            </button>
        </form>

        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
            @if(isset($wishes) && $wishes->isNotEmpty())
                @foreach($wishes as $wish)
                <div class="bg-white border border-slate-200 p-4 rounded-xl shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-bold text-slate-900 text-sm">{{ $wish->nama }}</p>
                        <span class="text-[10px] text-slate-400">{{ $wish->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-600 text-xs leading-relaxed">{{ $wish->ucapan }}</p>
                </div>
                @endforeach
            @else
                <p class="text-center text-slate-400 text-xs py-4">Belum ada ucapan. Berikan doa pertama Anda!</p>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 text-center bg-slate-900 text-slate-400 text-xs">
        <p>The Wedding of {{ $coupleTitle }}</p>
        <p class="mt-1">Powered by <strong>{{ $store_name }}</strong></p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800, once: true });</script>
</body>

</html>
