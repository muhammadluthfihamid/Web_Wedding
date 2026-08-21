<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('components.og-meta')
    @php
        $anak = isset($biodataPria) && $biodataPria->isNotEmpty() ? $biodataPria->first() : null;
        $orangtua = isset($biodataWanita) && $biodataWanita->isNotEmpty() ? $biodataWanita->first() : null;
        $namaAnak = $anak ? $anak->nama : 'Ananda';
        $namaLengkap = $anak ? ($anak->nama_lengkap ?? $anak->nama) : 'Ananda';
        $firstInfo = isset($infos) && $infos->isNotEmpty() ? $infos->first() : null;
        $guestTo = request('to') ?: null;
    @endphp

    <title>Undangan Khitanan {{ $namaLengkap }} | {{ $store_name }}</title>

    <!-- Tailwind & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Reem+Kufi:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-arabic { font-family: 'Amiri', serif; }
        .font-kufi { font-family: 'Reem Kufi', sans-serif; }
        .bg-islamic {
            background-color: #064e3b;
            background-image: radial-gradient(#10b981 0.75px, transparent 0.75px), radial-gradient(#10b981 0.75px, #064e3b 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
        }
        .text-gold {
            background: linear-gradient(135deg, #fef08a 0%, #eab308 50%, #ca8a04 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-emerald-950 text-slate-100 min-h-screen">

    {{-- Hero Section --}}
    <section class="min-h-screen bg-islamic flex flex-col justify-between p-6 text-center relative overflow-hidden">
        <div class="pt-8">
            <p class="font-arabic text-3xl md:text-4xl text-amber-300 mb-2">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</p>
            <span class="inline-block px-4 py-1.5 bg-emerald-900/80 border border-amber-400/40 rounded-full text-xs font-bold tracking-widest text-amber-300 uppercase">
                Walimatul Khitan
            </span>
        </div>

        <div class="py-12" data-aos="zoom-in">
            <div class="w-32 h-32 md:w-40 md:h-40 mx-auto rounded-full p-1 bg-gradient-to-tr from-amber-300 to-emerald-500 mb-6 shadow-2xl overflow-hidden">
                <img src="{{ $anak && $anak->foto ? asset('storage/'.$anak->foto) : asset('assets/img/hero/default-child.png') }}" 
                     alt="{{ $namaLengkap }}" 
                     class="w-full h-full object-cover rounded-full"
                     onerror="this.src='https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80'">
            </div>
            <p class="text-emerald-200 text-sm font-semibold tracking-wide uppercase">Tasyakuran Khitanan</p>
            <h1 class="text-4xl md:text-6xl font-extrabold text-gold font-kufi my-3">{{ $namaLengkap }}</h1>
            @if($orangtua)
                <p class="text-slate-300 text-sm md:text-base">Putra dari Bpk. {{ $orangtua->nama_ayah ?? '...' }} & Ibu {{ $orangtua->nama_ibu ?? $orangtua->nama }}</p>
            @endif
        </div>

        @if($guestTo)
        <div class="bg-emerald-900/90 border border-amber-400/30 rounded-2xl p-4 max-w-md mx-auto mb-8 shadow-xl">
            <p class="text-xs text-emerald-300">Kepada Yth. Bapak/Ibu/Saudara/i:</p>
            <p class="text-lg font-bold text-amber-300 mt-1">{{ $guestTo }}</p>
        </div>
        @endif

        <div class="pb-8">
            <a href="#doa" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-400 to-yellow-600 text-emerald-950 rounded-full font-bold text-sm shadow-lg hover:brightness-110 transition-all">
                <i class="fas fa-envelope-open"></i> Buka Undangan
            </a>
        </div>
    </section>

    {{-- Doa & Ayat --}}
    <section id="doa" class="py-16 px-6 max-w-3xl mx-auto text-center" data-aos="fade-up">
        <div class="bg-emerald-900/50 border border-amber-400/20 rounded-3xl p-8 shadow-xl backdrop-blur-sm">
            <i class="fas fa-hands-praying text-4xl text-amber-400 mb-4"></i>
            <p class="font-arabic text-2xl md:text-3xl text-amber-200 leading-relaxed mb-4">
                اللَّهُمَّ بَارِكْ فِي صَبِيِّنَا وَاجْعَلْهُ مِنَ الصَّالِحِينَ
            </p>
            <p class="text-slate-300 text-sm leading-relaxed italic">
                "Ya Allah, berkahilah anak kami ini dan jadikanlah ia termasuk dalam golongan orang-orang yang soleh, taat kepada-Mu, serta berbakti kepada kedua orang tuanya."
            </p>
        </div>
    </section>

    {{-- Tanggal & Lokasi Acara --}}
    @if($firstInfo)
    <section class="py-16 px-6 bg-emerald-900/30 border-y border-amber-400/10">
        <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold text-gold font-kufi mb-8">Waktu & Tempat Acara</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Acara Khitan / Syukuran --}}
                <div class="bg-emerald-900/70 border border-amber-400/30 p-6 rounded-2xl text-left">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-400/20 flex items-center justify-center text-amber-300 text-lg">
                            <i class="fas fa-calendar-star"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-amber-300 text-lg">Acara Syukuran Khitan</h3>
                            <p class="text-xs text-emerald-300">{{ $firstInfo->tanggal_akad ? \Carbon\Carbon::parse($firstInfo->tanggal_akad)->translatedFormat('l, d F Y') : '-' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-300 mb-2"><i class="far fa-clock mr-2 text-amber-400"></i> {{ $firstInfo->waktu_akad ?? '08:00 WIB - Selesai' }}</p>
                    <p class="text-sm text-slate-300"><i class="fas fa-location-dot mr-2 text-amber-400"></i> {{ $firstInfo->lokasi_akad ?? 'Kediaman Mempelai' }}</p>
                </div>

                {{-- Resepsi / Ramah Tamah --}}
                <div class="bg-emerald-900/70 border border-amber-400/30 p-6 rounded-2xl text-left">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-400/20 flex items-center justify-center text-amber-300 text-lg">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-amber-300 text-lg">Resepsi Syukuran</h3>
                            <p class="text-xs text-emerald-300">{{ $firstInfo->tanggal_resepsi ? \Carbon\Carbon::parse($firstInfo->tanggal_resepsi)->translatedFormat('l, d F Y') : '-' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-300 mb-2"><i class="far fa-clock mr-2 text-amber-400"></i> {{ $firstInfo->waktu_resepsi ?? '11:00 WIB - Selesai' }}</p>
                    <p class="text-sm text-slate-300"><i class="fas fa-location-dot mr-2 text-amber-400"></i> {{ $firstInfo->lokasi_resepsi ?? 'Kediaman Mempelai' }}</p>
                </div>
            </div>

            @if($firstInfo->link_maps)
            <div class="mt-8">
                <a href="{{ $firstInfo->link_maps }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-400 text-emerald-950 font-bold rounded-xl text-sm shadow-md hover:bg-amber-300 transition-all">
                    <i class="fas fa-map-marked-alt"></i> Buka Google Maps
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Gift / Angpao Khitan --}}
    @if(isset($gifts) && $gifts->isNotEmpty())
    <section class="py-16 px-6 max-w-3xl mx-auto text-center" data-aos="fade-up">
        <h2 class="text-3xl font-extrabold text-gold font-kufi mb-4">Kado & Hadiah Khitan</h2>
        <p class="text-slate-300 text-sm mb-8">Bagi Bapak/Ibu/Saudara/i yang ingin memberikan hadiah untuk Ananda:</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($gifts as $gift)
            <div class="bg-emerald-900/60 border border-amber-400/30 p-6 rounded-2xl">
                <p class="text-xs text-amber-300 font-bold uppercase tracking-wider mb-1">{{ $gift->nama_bank }}</p>
                <p class="text-2xl font-bold text-white tracking-widest my-2">{{ $gift->no_rekening }}</p>
                <p class="text-sm text-slate-300">a/n {{ $gift->atas_nama }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Ucapan & Doa Tamu --}}
    <section class="py-16 px-6 max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gold font-kufi mb-2">Kiriman Ucapan & Doa</h2>
            <p class="text-slate-300 text-sm">Tuliskan doa dan ucapan selamat khitanan untuk Ananda {{ $namaLengkap }}.</p>
        </div>

        <form action="{{ route('wish.storePublic') }}" method="POST" class="bg-emerald-900/60 border border-amber-400/30 p-6 rounded-2xl space-y-4 mb-8">
            @csrf
            <div>
                <input type="text" name="nama" placeholder="Nama Anda" required class="w-full px-4 py-3 bg-emerald-950/80 border border-amber-400/30 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm">
            </div>
            <div>
                <textarea name="ucapan" rows="3" placeholder="Tulis ucapan & doa Anda..." required class="w-full px-4 py-3 bg-emerald-950/80 border border-amber-400/30 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-400 text-sm"></textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-amber-400 text-emerald-950 font-bold rounded-xl text-sm hover:bg-amber-300 transition-colors">
                <i class="fas fa-paper-plane mr-1"></i> Kirim Ucapan
            </button>
        </form>

        {{-- List Wishes --}}
        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
            @if(isset($wishes) && $wishes->isNotEmpty())
                @foreach($wishes as $wish)
                <div class="bg-emerald-900/40 border border-emerald-800 p-4 rounded-xl">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-bold text-amber-300 text-sm">{{ $wish->nama }}</p>
                        <span class="text-[10px] text-slate-400">{{ $wish->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-200 text-xs leading-relaxed">{{ $wish->ucapan }}</p>
                </div>
                @endforeach
            @else
                <p class="text-center text-slate-400 text-xs py-4">Belum ada ucapan. Jadilah yang pertama memberikan doa!</p>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 text-center bg-emerald-950 text-slate-500 text-xs border-t border-amber-400/10">
        <p>Tasyakuran Khitanan {{ $namaLengkap }}</p>
        <p class="mt-1">Powered by <strong>{{ $store_name }}</strong></p>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800, once: true });</script>
</body>

</html>
