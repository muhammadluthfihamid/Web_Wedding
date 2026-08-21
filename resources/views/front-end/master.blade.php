<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('components.og-meta')
    @php
        $namaIstri = null;
        $namaPria = null;
        if (isset($infos) && $infos->isNotEmpty()) {
            $firstInfo = $infos->first();
            $namaIstri = $firstInfo->nama_pengantin_istri;
            $namaPria = $firstInfo->nama_pengantin_pria;
        }
        if (!$namaIstri && isset($biodataWanita) && $biodataWanita->isNotEmpty()) {
            $namaIstri = $biodataWanita->first()->nama;
        }
        if (!$namaPria && isset($biodataPria) && $biodataPria->isNotEmpty()) {
            $namaPria = $biodataPria->first()->nama;
        }
        $namaIstri = $namaIstri ?: 'Mempelai Wanita';
        $namaPria = $namaPria ?: 'Mempelai Pria';
        $coupleTitle = $namaIstri . ' & ' . $namaPria;
    @endphp

    <title>Undangan Pernikahan {{ $coupleTitle }} | {{ $store_name }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Noto+Naskh+Arabic:wght@400;700&family=Sacramento&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- simplyCountdown -->
    <link rel="stylesheet" href="{{ asset('assets/css/simplyCountdown.theme.default.css') }}" />
    <script src="{{ asset('assets/js/simplyCountdown.min.js') }}"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- SweetAlert2 for Toast Notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Main Styles (with cache busting for InfinityFree/cPanel) -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
</head>

<body>
    @include('components.preloader')

    <!-- Modern Animated Ambient Background & Sparkles -->
    <div class="modern-animated-bg">
        <div class="ambient-orb ambient-orb-1"></div>
        <div class="ambient-orb ambient-orb-2"></div>
        <div class="ambient-orb ambient-orb-3"></div>
        <div class="ambient-orb ambient-orb-4"></div>
    </div>

    <!-- Floating Sparkle Particles -->
    <div class="animated-sparkles">
        <div class="sparkle" style="top: 15%; left: 10%; animation-delay: 0s; animation-duration: 12s;"></div>
        <div class="sparkle" style="top: 30%; right: 12%; animation-delay: 2s; animation-duration: 16s;"></div>
        <div class="sparkle" style="top: 48%; left: 18%; animation-delay: 4s; animation-duration: 14s;"></div>
        <div class="sparkle" style="top: 68%; right: 8%; animation-delay: 1s; animation-duration: 17s;"></div>
        <div class="sparkle" style="top: 85%; left: 6%; animation-delay: 3s; animation-duration: 13s;"></div>
    </div>

    <!-- Floating Leaf Particles for natural, non-empty background decoration -->
    <div class="leaf-decorations">
        <svg class="leaf-particle" style="top: 12%; left: 6%; width: 25px; height: 25px; animation-delay: 0s; animation-duration: 14s;" viewBox="0 0 24 24">
            <path fill="#586b8c" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
        <svg class="leaf-particle" style="top: 28%; right: 8%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 18s;" viewBox="0 0 24 24">
            <path fill="#8ca4d0" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
        <svg class="leaf-particle" style="top: 42%; left: 9%; width: 28px; height: 28px; animation-delay: 4s; animation-duration: 16s;" viewBox="0 0 24 24">
            <path fill="#586b8c" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
        <svg class="leaf-particle" style="top: 58%; right: 12%; width: 22px; height: 22px; animation-delay: 1s; animation-duration: 15s;" viewBox="0 0 24 24">
            <path fill="#8ca4d0" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
        <svg class="leaf-particle" style="top: 72%; left: 5%; width: 24px; height: 24px; animation-delay: 5s; animation-duration: 20s;" viewBox="0 0 24 24">
            <path fill="#586b8c" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
        <svg class="leaf-particle" style="top: 88%; right: 6%; width: 26px; height: 26px; animation-delay: 3s; animation-duration: 17s;" viewBox="0 0 24 24">
            <path fill="#8ca4d0" d="M17,8C8,10 5.9,16.17 3.82,21.34L2.18,20.66C4.26,15.49 6.36,9.33 15.36,7.33L12,4H18V10L17,8Z" />
        </svg>
    </div>

    <!-- ==========================================================================
       HERO COVER SECTION
       ========================================================================== -->
    <section id="hero" class="hero w-100 min-vh-100 p-3 mx-auto text-center d-flex justify-content-center align-items-center text-white">
        <div class="bg-decor-left"></div>
        <div class="bg-decor-right"></div>
        <main class="hero-card" data-aos="zoom-in" data-aos-duration="1500">
            <h3 style="font-family: 'Sacramento', cursive; font-size: clamp(1.8rem, 5vw, 2.2rem); color: var(--gold-light); font-weight: normal; margin-bottom: 0.25rem;">Undangan</h3>
            @foreach ($infos as $info)
            <h1 class="hero-title-script my-2">
                {{ $info->nama_pengantin_istri }} <br> & <br> {{ $info->nama_pengantin_pria }}
            </h1>
            @endforeach

            <a href="#home" class="btn btn-lg mt-1 mb-4" onClick="enableScroll()" style="text-transform: none !important; padding: 0.75rem 2.2rem; border-radius: 50px; font-weight: 500; font-size: 1rem; box-shadow: 0 8px 25px rgba(62, 75, 109, 0.25);">
                <i class="bi bi-envelope-open-fill me-2"></i> Buka Undangan
            </a>

            <div class="recipient-box mt-2">
                <p class="mb-1 text-white-50" style="font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase;">Kepada</p>
                <p class="mb-1 text-white-50" id="pronoun-container" style="font-size: 0.95rem; font-weight: 500; letter-spacing: 0.5px;">Yth. Bapak/Ibu/Saudara/i</p>
                <h2 class="my-2 text-white" id="guest-name-container" style="font-size: clamp(1.4rem, 5.5vw, 1.85rem); font-family: 'Playfair Display', serif; font-weight: 700; text-shadow: 1px 2px 5px rgba(0,0,0,0.2);">
                    <span></span>
                </h2>
                <p class="mt-3 text-white-50" style="font-size: 0.85rem; line-height: 1.6; max-width: 320px; margin: 0 auto; letter-spacing: 0.2px;">
                    Tanpa mengurangi rasa hormat, kami mengundang Anda untuk hadir di acara pernikahan kami
                </p>
            </div>
        </main>
    </section>

    <!-- Navigation Bar -->
    @include('front-end.navbar')

    <!-- ==========================================================================
       HOME SECTION (THE COUPLE)
       ========================================================================== -->
    <section id="home" class="home">
        <div class="bg-decor-left"></div>
        <div class="bg-decor-right"></div>
        <div class="container">
            <!-- Title "The Wedding" -->
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-md-10 text-center">
                    <h2 class="mb-4 section-heading-script">The Wedding</h2>
                </div>
            </div>

            <!-- Names, Date, Save The Date Badge, and Countdown -->
            <div class="row justify-content-center mt-3 mt-md-5" data-aos="fade-up">
                <div class="col-md-10 text-center">
                    <div class="text-center">
                        <div class="wreath-wrapper">
                            <img src="{{ asset('assets/img/wa-preview.png') }}" alt="The Wedding Wreath" class="wreath-img">
                        </div>
                    </div>
                    @foreach ($infos as $info)
                    <h2 class="mb-0 couple-name-script">
                        {{ $info->nama_pengantin_istri }}
                    </h2>
                    <div style="font-family: 'Sacramento', cursive; font-size: clamp(1.6rem, 5vw, 2rem); color: var(--sage-medium); line-height: 1.1; margin: 0.1rem 0;">
                        &amp;
                    </div>
                    <h2 class="mb-1 couple-name-script">
                        {{ $info->nama_pengantin_pria }}
                    </h2>
                    <h3 class="mb-3" style="font-family: 'Playfair Display', serif; font-size: clamp(1.1rem, 4vw, 1.35rem); color: var(--sage-medium); font-weight: 600;">
                        {{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('d F Y') }}
                    </h3>

                    <div class="mb-4 mt-2">
                        <span class="badge px-3 px-md-4 py-2.5 text-white" style="background-color: var(--sage-dark); font-size: 0.95rem; font-weight: 500; border-radius: 20px; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(62,75,109,0.15); display: inline-block;">Save The Date</span>
                    </div>

                    <!-- Circular Hitung Mundur Acara -->
                    <div class="mt-4 mb-3 d-flex justify-content-center">
                        <div class="simply-countdown"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Couple Profiles (BRIDE FIRST, THEN GROOM) -->
            <div class="row justify-content-center align-items-center mt-3 g-4">

                <!-- BRIDE (WANITA) -->
                <div class="col-12 col-lg-5 order-1 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="couple-card text-center">
                        <div class="photo-container">
                            @foreach ($biodataWanita as $wanita)
                            <img src="{{ $wanita->foto ? asset('storage/' . $wanita->foto) : asset('assets/img/ukhti.png') }}"
                                class="wedding-img-circle" loading="lazy" width="220" height="310" alt="Foto Pengantin Wanita">
                            @endforeach
                        </div>
                        @foreach ($biodataWanita as $wanita)
                        <h3 class="mt-3">{{ $wanita->nama }}</h3>
                        <p class="text-muted small px-3">{{ $wanita->deskripsi }}</p>
                        <div class="parents-info mt-3 pt-3 border-top border-light">
                            <p class="small text-muted mb-1">Putri dari Pasangan</p>
                            <p class="fw-bold mb-0 text-dark">{{ $wanita->bapak }}</p>
                            <p class="small text-muted my-1">&</p>
                            <p class="fw-bold text-dark mb-0">{{ $wanita->ibu }}</p>
                            @if(!empty($wanita->asal))
                            <p class="small mt-2 mb-0" style="color: var(--gold); font-weight: 500;">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $wanita->asal }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- HEART / WEDDING RINGS SPLITTER -->
                <div class="col-12 col-lg-2 order-2 my-4 my-lg-0" data-aos="zoom-in">
                    <div class="rings-divider">
                        <div class="heart-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                    </div>
                </div>

                <!-- GROOM (PRIA) -->
                <div class="col-12 col-lg-5 order-3" data-aos="fade-left">
                    <div class="couple-card text-center">
                        <div class="photo-container">
                            @foreach ($biodataPria as $pria)
                            <img src="{{ $pria->foto ? asset('storage/' . $pria->foto) : asset('assets/img/me.png') }}"
                                class="wedding-img-circle" loading="lazy" width="220" height="310" alt="Foto Pengantin Pria">
                            @endforeach
                        </div>
                        @foreach ($biodataPria as $pria)
                        <h3 class="mt-3">{{ $pria->nama }}</h3>
                        <p class="text-muted small px-3">{{ $pria->deskripsi }}</p>
                        <div class="parents-info mt-3 pt-3 border-top border-light">
                            <p class="small text-muted mb-1">Putra dari Pasangan</p>
                            <p class="fw-bold mb-0 text-dark">{{ $pria->bapak }}</p>
                            <p class="small text-muted my-1">&</p>
                            <p class="fw-bold text-dark mb-0">{{ $pria->ibu }}</p>
                            @if(!empty($pria->asal))
                            <p class="small mt-2 mb-0" style="color: var(--gold); font-weight: 500;">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $pria->asal }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>


        </div>
    </section>

    <!-- ==========================================================================
       DOA / DALIL SECTION
       ========================================================================== -->
    @php
    $infoItem = isset($infos) && $infos->count() > 0 ? $infos->first() : null;
    $teksArab = $infoItem?->teks_arab ?: 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ';
    $salamPembuka = $infoItem?->salam_pembuka ?: "Assalamu'alaikum Warahmatullahi Wabarakatuh";
    $teksPembuka = $infoItem?->teks_pembuka ?: 'Dengan memohon rahmat dan ridho Allah SWT yang telah menciptakan Makhluk-Nya secara berpasang-pasangan, kami bermaksud menyelenggarakan acara Walimatul Ursy.';
    $teksPenutup = $infoItem?->teks_penutup ?: 'Kami memohon do\'a restu agar menjadi keluarga yang Sakinah Mawaddah Warahmah. Atas perhatiannya, kami ucapkan terimakasih.';
    $salamPenutup = $infoItem?->salam_penutup ?: "Wassalamu'alaikum Warahmatullahi Wabarakatuh";
    @endphp
    <section id="doa" class="doa py-4 py-md-5" style="background-color: transparent;">
        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-12 col-md-10 col-lg-8 text-center">
                    <div class="couple-card p-4 p-md-5 shadow-sm" style="background: var(--cream-accent); border: 1px solid rgba(124, 142, 122, 0.15); border-radius: 30px;">

                        <!-- Calligraphy Bismillah / Teks Arab -->
                        @if ($teksArab)
                        <p class="mb-4 text-center arabic-text-responsive fw-bold" dir="rtl" style="color: var(--sage-dark); letter-spacing: 0.5px;">
                            {!! nl2br(e($teksArab)) !!}
                        </p>
                        @endif

                        @if ($salamPembuka)
                        <p class="fw-bold mb-4" style="font-size: 1.05rem; color: var(--sage-dark); letter-spacing: 0.5px;">
                            {{ $salamPembuka }}
                        </p>
                        @endif

                        @if ($teksPembuka)
                        <p class="mb-4 text-muted" style="font-size: 0.98rem; line-height: 1.8; letter-spacing: 0.2px;">
                            {!! nl2br(e($teksPembuka)) !!}
                        </p>
                        @endif

                        @if ($teksPenutup)
                        <p class="mb-4 text-muted" style="font-size: 0.98rem; line-height: 1.8; letter-spacing: 0.2px;">
                            {!! nl2br(e($teksPenutup)) !!}
                        </p>
                        @endif

                        @if ($salamPenutup)
                        <p class="fw-bold mt-4 mb-0" style="font-size: 1.05rem; color: var(--sage-dark); letter-spacing: 0.5px;">
                            {{ $salamPenutup }}
                        </p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================================================
       INFO SECTION (EVENTS & MAPS)
       ========================================================================== -->
    @php
    $info = isset($infos) && $infos->count() > 0 ? $infos->first() : null;
    @endphp

    @if($info)
    <section id="info" class="info py-5">
        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-12 col-md-10 col-lg-8 text-center">
                    <h2 class="fw-bold mb-3">Informasi Acara</h2>
                    <p class="alamat mb-4">
                        <i class="bi bi-geo-alt-fill me-2 text-warning"></i>
                        {{ $info->alamat }}
                    </p>

                    {{-- Google Maps Embed - Dinamis dari Koordinat Database --}}
                    <div class="info-map-container mb-4">
                        <div class="ratio ratio-16x9 ratio-md-21x9">
                            @if($info->latitude && $info->longitude)
                            {{-- Embed berdasarkan koordinat yang sudah tersimpan --}}
                            <iframe
                                src="https://maps.google.com/maps?q={{ $info->latitude }},{{ $info->longitude }}&z=16&output=embed"
                                style="border:0;" allowfullscreen loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            @else
                            {{-- Fallback: embed berdasarkan teks alamat --}}
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($info->alamat) }}&output=embed"
                                style="border:0;" allowfullscreen loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Petunjuk Arah - Dinamis --}}
                    @if($info->latitude && $info->longitude)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $info->latitude }},{{ $info->longitude }}"
                        target="_blank" class="btn btn-outline-info mb-4">
                        <i class="bi bi-map-fill me-2"></i> Petunjuk Arah Google Maps
                    </a>
                    @else
                    <a href="https://www.google.com/maps/search/{{ urlencode($info->alamat) }}"
                        target="_blank" class="btn btn-outline-info mb-4">
                        <i class="bi bi-map-fill me-2"></i> Cari Lokasi di Google Maps
                    </a>
                    @endif

                    <p class="description small text-white-50 px-lg-5">
                        Diharapkan untuk memperhatikan alamat dan tanggal pelaksanaan. Apabila ada perubahan lokasi,
                        akan kami informasikan kembali secara berkala.
                    </p>
                </div>
            </div>

            <!-- Event Cards for Akad & Resepsi -->
            <div class="row justify-content-center mt-4 mt-md-5 g-4">
                @php
                // Calendar link generator parameters
                $eventTitle = 'Pernikahan ' . $info->nama_pengantin_pria . ' & ' . $info->nama_pengantin_istri;
                $dateStr = \Carbon\Carbon::parse($info->tanggal_pernikahan)->format('Ymd');

                $startAkad = \Carbon\Carbon::parse($info->mulai_akad)->format('Hi') . '00';
                $endAkad = \Carbon\Carbon::parse($info->selesai_akad)->format('Hi') . '00';
                $gCalUrlAkad = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($eventTitle . ' - Akad Nikah') . "&dates=" . $dateStr . "T" . $startAkad . "/" . $dateStr . "T" . $endAkad . "&details=Mohon+doa+dan+restu&location=" . urlencode($info->alamat);

                $startResepsi = \Carbon\Carbon::parse($info->mulai_resepsi)->format('Hi') . '00';
                $gCalUrlResepsi = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($eventTitle . ' - Resepsi Pernikahan') . "&dates=" . $dateStr . "T" . $startResepsi . "/" . $dateStr . "T220000&details=Mohon+doa+dan+restu&location=" . urlencode($info->alamat);
                @endphp

                <!-- AKAD CARD -->
                <div class="col-12 col-md-6 col-lg-5" data-aos="flip-left">
                    <div class="card event-card text-center h-100">
                        <div class="card-header">
                            Akad Nikah
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="row g-2 align-items-center justify-content-center">
                                <div class="col-6 border-end border-light-subtle px-1 px-sm-2">
                                    <i class="bi bi-clock d-block mb-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($info->mulai_akad)->format('H.i') }} -
                                        {{ \Carbon\Carbon::parse($info->selesai_akad)->format('H.i') }}</span>
                                </div>
                                <div class="col-6 px-1 px-sm-2">
                                    <i class="bi bi-calendar3 d-block mb-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('l') }}<br>
                                        <strong>{{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('d F Y') }}</strong></span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ $gCalUrlAkad }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-calendar-check-fill me-1"></i> Simpan ke Kalender
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            Diharapkan menjaga kekhidmatan selama prosesi Akad.
                        </div>
                    </div>
                </div>

                <!-- RESEPSI CARD -->
                <div class="col-12 col-md-6 col-lg-5" data-aos="flip-right">
                    <div class="card event-card text-center h-100">
                        <div class="card-header">
                            Resepsi Pernikahan
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="row g-2 align-items-center justify-content-center">
                                <div class="col-6 border-end border-light-subtle px-1 px-sm-2">
                                    <i class="bi bi-clock d-block mb-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($info->mulai_resepsi)->format('H.i') }} - Selesai</span>
                                </div>
                                <div class="col-6 px-1 px-sm-2">
                                    <i class="bi bi-calendar3 d-block mb-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('l') }}<br>
                                        <strong>{{ \Carbon\Carbon::parse($info->tanggal_pernikahan)->translatedFormat('d F Y') }}</strong></span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ $gCalUrlResepsi }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-calendar-check-fill me-1"></i> Simpan ke Kalender
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            Kehadiran Anda merupakan kehormatan terbesar bagi kami.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ==========================================================================
       STORY SECTION (LOVE JOURNEY)
       ========================================================================== -->
    <section id="story" class="story">
        <div class="bg-decor-left"></div>
        <div class="bg-decor-right"></div>
        <div class="container">
            @if($story)
            <div class="row justify-content-center mb-4 mb-md-5" data-aos="fade-up">
                <div class="col-md-8 col-10 text-center">
                    <span class="subtitle">Bagaimana Cinta Kami Bersemi</span>
                    <h2>Cerita Kami</h2>
                    <p class="text-muted mt-3">{{ $story->deskripsi }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <ul class="timeline">

                        <!-- BERTEMU -->
                        <li data-aos="fade-up">
                            <div class="timeline-badge">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div class="timeline-panel">
                                @if($story->foto_bertemu)
                                <div class="timeline-img-wrapper mb-3 text-center">
                                    <img src="{{ asset('storage/' . $story->foto_bertemu) }}" class="img-fluid rounded" loading="lazy" decoding="async" alt="Foto Bertemu" style="max-height: 200px; object-fit: cover; border-radius: 12px; width: 100%;">
                                </div>
                                @endif
                                <div class="timeline-heading">
                                    <h3>{{ $story->judul_bertemu }}</h3>
                                    <span>{{ \Carbon\Carbon::parse($story->tgl_bertemu)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="timeline-body">
                                    <p>{{ $story->note_bertemu }}</p>
                                </div>
                            </div>
                        </li>

                        <!-- SERIUS -->
                        <li class="timeline-inverted" data-aos="fade-up">
                            <div class="timeline-badge">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div class="timeline-panel">
                                @if($story->foto_serius)
                                <div class="timeline-img-wrapper mb-3 text-center">
                                    <img src="{{ asset('storage/' . $story->foto_serius) }}" class="img-fluid rounded" loading="lazy" decoding="async" alt="Foto Serius" style="max-height: 200px; object-fit: cover; border-radius: 12px; width: 100%;">
                                </div>
                                @endif
                                <div class="timeline-heading">
                                    <h3>{{ $story->judul_serius }}</h3>
                                    <span>{{ \Carbon\Carbon::parse($story->tgl_serius)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="timeline-body">
                                    <p>{{ $story->note_serius }}</p>
                                </div>
                            </div>
                        </li>

                        <!-- TUNANGAN -->
                        <li data-aos="fade-up">
                            <div class="timeline-badge">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <div class="timeline-panel">
                                @if($story->foto_tunangan)
                                <div class="timeline-img-wrapper mb-3 text-center">
                                    <img src="{{ asset('storage/' . $story->foto_tunangan) }}" class="img-fluid rounded" loading="lazy" decoding="async" alt="Foto Tunangan" style="max-height: 200px; object-fit: cover; border-radius: 12px; width: 100%;">
                                </div>
                                @endif
                                <div class="timeline-heading">
                                    <h3>{{ $story->judul_tunangan }}</h3>
                                    <span>{{ \Carbon\Carbon::parse($story->tgl_tunangan)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="timeline-body">
                                    <p>{{ $story->note_tunangan }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </section>


    <!-- ==========================================================================
       RSVP SECTION
       ========================================================================== -->
    <section id="rsvp" class="rsvp">
        <div class="bg-decor-left"></div>
        <div class="bg-decor-right"></div>
        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-md-8 col-10 text-center">
                    <h2>Konfirmasi Kehadiran & Doa Restu</h2>
                    <p class="lead">Isi formulir di bawah ini untuk mengonfirmasi kehadiran serta memberikan ucapan & doa restu bagi kedua mempelai.</p>
                </div>
            </div>

            <!-- Single Unified RSVP & Wishes Form -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 text-center" data-aos="fade-up">
                    <div class="rsvp-form-container text-start mb-5" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <form id="wish-form" action="{{ route('wish.storePublic') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="wish_nama" class="form-label">Nama Anda</label>
                                    <input type="text" class="form-control" id="wish_nama" name="nama" required placeholder="Nama Lengkap">
                                </div>
                                <div class="col-md-3">
                                    <label for="wish_kehadiran" class="form-label">Konfirmasi</label>
                                    <select name="kehadiran" id="wish_kehadiran" class="form-select" required>
                                        <option value="" disabled selected>Pilih salah satu</option>
                                        <option value="1">Hadir</option>
                                        <option value="0">Tidak Hadir</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="wish_jumlah" class="form-label">Jumlah Tamu</label>
                                    <select class="form-select" id="wish_jumlah" name="jumlah">
                                        <option value="1" selected>1 Orang</option>
                                        <option value="2">2 Orang</option>
                                        <option value="3">3 Orang</option>
                                        <option value="4">4 Orang</option>
                                        <option value="5">5 Orang</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="wish_ucapan" class="form-label">Ucapan & Doa Restu</label>
                                    <textarea class="form-control" id="wish_ucapan" name="ucapan" rows="3" required placeholder="Tulis ucapan selamat & doa restu Anda..."></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary px-4 px-md-5 py-2.5 w-100 w-sm-auto"><i class="bi bi-send-fill me-2"></i> Kirim Konfirmasi & Ucapan</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Wishes Feed Header -->
                    <h3 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: var(--gold-light);">Ucapan & Doa Restu Terbaru</h3>

                    <!-- Wishes Feed -->
                    <div class="wishes-container text-start" id="wishes-feed">
                        @if($wishes->isEmpty())
                        <div class="text-center text-white-50 py-4" id="empty-wishes-alert">
                            <i class="bi bi-chat-quote fs-1 d-block mb-2"></i>
                            <span>Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</span>
                        </div>
                        @else
                        @foreach($wishes as $wish)
                        <div class="wish-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">{{ $wish->nama }}</h5>
                                <span class="badge {{ $wish->kehadiran ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }}" style="font-size: 0.75rem; padding: 0.25em 0.6em;">
                                    {{ $wish->kehadiran ? 'Hadir' : 'Tidak Hadir' }}
                                </span>
                            </div>
                            <p class="mb-1" style="font-size: 0.95rem; line-height: 1.5; color: #444;">{{ $wish->ucapan }}</p>
                            <div class="text-end">
                                <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $wish->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ==========================================================================
       GIFTS SECTION (DIGITAL WALLET CARDS)
       ========================================================================== -->
    <section id="gifts" class="gifts">
        <div class="container">
            @if(isset($gifts) && $gifts->count() > 0)
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-md-8 col-10 text-center">
                    <span class="subtitle">Ungkapan Tanda Kasih</span>
                    <h2>Kirim Hadiah</h2>
                    <p class="text-muted mt-3 mb-4">{{ $gifts->whereNotNull('deskripsi')->first()->deskripsi ?? 'Bagi bapak/ibu/saudara/i yang ingin memberikan hadiah pernikahan, dapat melalui rekening/e-wallet di bawah ini:' }}</p>
                </div>
            </div>

            <div class="row justify-content-center text-center g-4" data-aos="zoom-in">
                @foreach ($gifts as $gift)
                <div class="col-md-6 col-lg-5">

                    <!-- Digital Debit Card layout with custom admin background color -->
                    <div class="digital-card mb-4" style="background: <?= $gift->bg_color ?? 'linear-gradient(135deg, #3e4b6d 0%, #252e45 100%)' ?>; border: 1px solid rgba(255,255,255,0.15);">
                        <div class="card-bank-name">{{ $gift->nama_bank }}</div>
                        <div class="card-chip"></div>
                        <div class="card-number-container">
                            <span class="card-number">{{ $gift->no_rek }}</span>
                        </div>
                        <div class="card-holder">Atas Nama</div>
                        <div class="card-holder-name me-5 me-sm-0">{{ $gift->nama }}</div>

                        <button class="btn-copy-card btn-sm" onclick="copyCardNumber('{{ $gift->no_rek }}', this)">
                            <i class="bi bi-clipboard me-1"></i> Salin
                        </button>
                    </div>

                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- ==========================================================================
       TURUT MENGUNDANG SECTION
       ========================================================================== -->
    @if(isset($turutMengundangs) && $turutMengundangs->count() > 0)
    <section id="turut-mengundang" class="turut-mengundang py-5">
        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-12 col-md-10 col-lg-8">
                    <!-- Main Container Card -->
                    <div class="couple-card p-3 p-sm-4 p-md-5 shadow-sm text-start" style="background: var(--cream-accent, #faf9f6); border: 1px solid rgba(124, 142, 122, 0.2); border-radius: 28px; box-shadow: 0 10px 35px rgba(0, 0, 0, 0.06);">

                        <!-- Verse Quote Header (QS. Ar Rum:21) -->
                        <div class="text-center mb-4 mb-md-5 pb-3 border-bottom border-light">
                            <h3 class="mb-3" style="font-family: 'Sacramento', 'Playfair Display', cursive, serif; font-size: clamp(1.6rem, 5vw, 2.1rem); color: var(--sage-dark, #2c3e50); font-weight: bold;">QS. Ar Rum:21</h3>
                            <p class="mb-3 arabic-text-responsive text-center" dir="rtl" style="color: var(--sage-dark, #2c3e50);">
                                وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُم مِّنْ أَنفُسِكُمْ أَزْوَاجًا لِّتَسْكُنُوا إِلَيْهَا وَجَعَلَ بَيْنَكُم مَّوَدَّةً وَرَحْمَةً ۚ إِنَّ فِي ذَٰلِكَ لَآيَاتٍ لِّقَوْمٍ يَتَفَكَّرُونَ
                            </p>
                            <p class="fst-italic px-1 px-md-4 mb-0" style="font-family: 'Georgia', 'Playfair Display', serif; font-size: 0.95rem; line-height: 1.8; color: #4a5568;">
                                "Dan diantara tanda – tanda kekuasaan-Nya ialah cipataan-Nya untukmu pasangan hidup dari jenismu sendiri, supaya kamu mendapatkan ketenangan hati dan dijadikan-Nya kasih sayang diatara kamu. Sesungguhnya yang demikian menjadi tanda- tanda kebesaran-Nya bagi orang – orang yang berfikir"
                            </p>
                        </div>

                        <!-- Turut Mengundang Numbered List -->
                        <div class="px-1 px-md-4">
                            <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #1a202c;">
                                Turut Mengundang:
                            </h4>
                            <ol class="ps-0 mb-0 list-unstyled" style="font-family: 'Georgia', serif; font-size: 0.98rem; color: #2d3748; line-height: 2;">
                                @foreach ($turutMengundangs as $item)
                                <li class="d-flex align-items-baseline mb-2">
                                    <span class="fw-bold me-2" style="min-width: 24px; color: var(--sage-dark, #2c3e50);">{{ $loop->iteration }}.</span>
                                    <span>
                                        <strong style="color: #1a202c; font-weight: 600;">{{ $item->nama }}</strong>
                                        @if($item->hubungan)
                                        <span class="text-muted ms-1">({{ $item->hubungan }})</span>
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                            </ol>
                        </div>

                        <!-- Closing Section (Ucapan Terima Kasih, Salam Penutup, & Hormat Kami) -->
                        @php
                        $infoItem = isset($infos) && $infos->count() > 0 ? $infos->first() : null;
                        $priaObj = isset($biodataPria) && $biodataPria->count() > 0 ? $biodataPria->first() : null;
                        $wanitaObj = isset($biodataWanita) && $biodataWanita->count() > 0 ? $biodataWanita->first() : null;
                        @endphp
                        <div class="mt-4 mt-md-5 pt-4 text-center border-top border-light">
                            <!-- Teks Penutup -->
                            <p class="mb-4 px-1 px-md-4" style="font-family: 'Georgia', serif; font-size: 0.98rem; color: #4a5568; line-height: 1.8;">
                                {{ $infoItem?->teks_penutup ?: "Atas kehadiran dan do'a restu dari Bapak/Ibu/Saudara/i sekalian, kami mengucapkan terima kasih." }}
                            </p>

                            <!-- Salam Penutup Arab / Teks -->
                            <p class="mb-4 fw-bold arabic-text-responsive text-center" dir="rtl" style="color: var(--sage-dark, #2c3e50);">
                                {{ $infoItem?->salam_penutup ?: 'وَ السَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ' }}
                            </p>

                            <!-- Hormat Kami & Parents -->
                            <div class="mb-4">
                                <p class="mb-2" style="font-family: 'Georgia', serif; font-size: 1rem; color: #2d3748; font-weight: 500;">Hormat kami,</p>

                                @if($wanitaObj && ($wanitaObj->bapak || $wanitaObj->ibu))
                                <p class="fw-bold mb-1" style="font-family: 'Georgia', serif; font-size: 1rem; color: #1a202c;">
                                    Bapak {{ $wanitaObj->bapak }} &amp; Ibu {{ $wanitaObj->ibu }}
                                </p>
                                @endif

                                @if($priaObj && ($priaObj->bapak || $priaObj->ibu))
                                <p class="fw-bold mb-0" style="font-family: 'Georgia', serif; font-size: 1rem; color: #1a202c;">
                                    Bapak {{ $priaObj->bapak }} &amp; Ibu {{ $priaObj->ibu }}
                                </p>
                                @endif
                            </div>

                            <!-- Mempelai Name & Photo Layout -->
                            <div class="mt-4 pt-2 text-center">
                                <!-- 1. Nama Pengantin Wanita (Atas) -->
                                <h2 class="mb-2 couple-name-script">
                                    {{ $wanitaObj?->nama ?: ($infoItem?->nama_pengantin_istri ?: 'Pengantin Wanita') }}
                                </h2>

                                <!-- 2. Foto Mempelai (Tengah) -->
                                <div class="d-flex justify-content-center align-items-center my-3">
                                    <div class="couple-photo-wrapper position-relative p-2" style="background: rgba(255, 255, 255, 0.95); border-radius: 50px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); border: 2px solid var(--gold, #c5a059); display: inline-block;">
                                        @if($wanitaObj?->foto && $priaObj?->foto)
                                        <div class="d-flex align-items-center gap-2 px-2">
                                            <img src="{{ asset('storage/' . $wanitaObj->foto) }}" class="rounded-circle shadow-sm mempelai-photo-closing" style="object-fit: cover; border: 2px solid #fff;" alt="Pengantin Wanita">
                                            <i class="bi bi-heart-fill text-danger fs-5 animate-pulse"></i>
                                            <img src="{{ asset('storage/' . $priaObj->foto) }}" class="rounded-circle shadow-sm mempelai-photo-closing" style="object-fit: cover; border: 2px solid #fff;" alt="Pengantin Pria">
                                        </div>
                                        @elseif($wanitaObj?->foto)
                                        <img src="{{ asset('storage/' . $wanitaObj->foto) }}" class="rounded-circle shadow-sm" style="width: 85px; height: 85px; object-fit: cover;" alt="Pengantin Wanita">
                                        @elseif($priaObj?->foto)
                                        <img src="{{ asset('storage/' . $priaObj->foto) }}" class="rounded-circle shadow-sm" style="width: 85px; height: 85px; object-fit: cover;" alt="Pengantin Pria">
                                        @else
                                        <img src="{{ asset('assets/img/thewedding.webp') }}" class="rounded-circle shadow-sm" style="width: 85px; height: 85px; object-fit: cover;" alt="Mempelai">
                                        @endif
                                    </div>
                                </div>

                                <!-- 3. Nama Pengantin Pria (Bawah) -->
                                <h2 class="mt-2 mb-0 couple-name-script">
                                    {{ $priaObj?->nama ?: ($infoItem?->nama_pengantin_pria ?: 'Pengantin Pria') }}
                                </h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    @include('front-end.footer')

    <!-- ==========================================================================
       FLOATING VINYL AUDIO CONTROLLER
       ========================================================================== -->
    @php
    $infoAudioItem = isset($infos) && $infos->count() > 0 ? $infos->first() : null;
    $musikUrl = $infoAudioItem?->musik_url
    ? (str_starts_with($infoAudioItem->musik_url, 'http') ? $infoAudioItem->musik_url : asset($infoAudioItem->musik_url))
    : asset('assets/audio/Bi Saraha.mp3');
    $isAudioActive = $infoAudioItem ? ($infoAudioItem->is_audio_active ?? true) : true;
    @endphp

    @if ($isAudioActive && $musikUrl)
    <div id="audio-container">
        <audio id="song" autoplay loop preload="auto">
            <source src="{{ $musikUrl }}" type="audio/mp3">
        </audio>

        <div class="audio-icon-wrapper" style="display: none;">
            <i class="bi bi-disc"></i>
        </div>
    </div>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 1000
        });
    </script>

    <!-- SimplyCountdown script -->
    @if (isset($infos[0]))
    <script>
        const eventDate = new window.Date("{{ $infos[0]->tanggal_pernikahan }}");
        simplyCountdown('.simply-countdown', {
            year: eventDate.getFullYear(),
            month: eventDate.getMonth() + 1,
            day: eventDate.getDate(),
            hours: 0,
            words: {
                days: {
                    singular: 'hari',
                    plural: 'hari'
                },
                hours: {
                    singular: 'jam',
                    plural: 'jam'
                },
                minutes: {
                    singular: 'menit',
                    plural: 'menit'
                },
                seconds: {
                    singular: 'detik',
                    plural: 'detik'
                }
            },
        });
    </script>
    @endif

    <!-- Copy to Clipboard helper with SweetAlert integration -->
    <script>
        function copyCardNumber(cardNumber, button) {
            navigator.clipboard.writeText(cardNumber).then(function() {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersalin';
                button.classList.add('bg-success');
                button.style.borderColor = 'transparent';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nomor rekening berhasil disalin!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-success');
                    button.style.borderColor = '';
                }, 2000);
            }, function(err) {
                console.error('Gagal menyalin rekening: ', err);
            });
        }
    </script>

    <!-- Scrolling, Audio and offcanvas control scripts -->
    <script>
        const stickyTop = document.querySelector('.sticky-top');
        const offcanvas = document.querySelector('.offcanvas');

        if (offcanvas && stickyTop) {
            offcanvas.addEventListener('show.bs.offcanvas', function() {
                stickyTop.style.overflow = 'visible';
            });

            offcanvas.addEventListener('hidden.bs.offcanvas', function() {
                stickyTop.style.overflow = 'hidden';
            });
        }
    </script>

    <script>
        const rootElement = document.querySelector(":root");
        const audioIconWrapper = document.querySelector('.audio-icon-wrapper');
        const audioIcon = document.querySelector('.audio-icon-wrapper i');
        const song = document.querySelector('#song');
        let isPlaying = false;

        function disableScroll() {
            scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            window.onscroll = function() {
                window.scrollTo(scrollTop, scrollLeft);
            }

            rootElement.style.scrollBehavior = 'auto';

            // Ensure navbars stay hidden on initial cover view
            const mobileNav = document.getElementById('mobile-bottom-nav');
            const desktopNav = document.getElementById('desktop-top-nav');
            if (mobileNav) {
                mobileNav.classList.add('d-none');
                mobileNav.classList.remove('d-flex');
            }
            if (desktopNav) {
                desktopNav.classList.add('d-none');
            }
        }

        function enableScroll() {
            window.onscroll = function() {}
            rootElement.style.scrollBehavior = 'smooth';

            // Reveal navbars smoothly upon clicking Buka Undangan
            const mobileNav = document.getElementById('mobile-bottom-nav');
            const desktopNav = document.getElementById('desktop-top-nav');
            if (mobileNav) {
                mobileNav.classList.remove('d-none');
                mobileNav.classList.add('d-md-none', 'd-flex');
            }
            if (desktopNav) {
                desktopNav.classList.remove('d-none');
            }

            playAudio();
        }

        function playAudio() {
            song.volume = 0.15;
            audioIconWrapper.style.display = 'flex';
            audioIconWrapper.classList.add('playing');
            song.play();
            isPlaying = true;
        }

        audioIconWrapper.onclick = function() {
            if (isPlaying) {
                song.pause();
                audioIconWrapper.classList.remove('playing');
                audioIcon.classList.remove('bi-disc');
                audioIcon.classList.add('bi-pause-circle');
            } else {
                song.play();
                audioIconWrapper.classList.add('playing');
                audioIcon.classList.add('bi-disc');
                audioIcon.classList.remove('bi-pause-circle');
            }

            isPlaying = !isPlaying;
        }

        disableScroll();
    </script>

    <!-- Form Submit Handlers via Ajax -->
    <script>
        window.addEventListener("load", function() {
            // Wishes & RSVP Form Submit Handler
            const wishForm = document.getElementById('wish-form');
            if (wishForm) {
                wishForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    if (window.hideGlobalPreloader) {
                        window.hideGlobalPreloader();
                    }

                    const submitBtn = wishForm.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...';
                    }

                    const data = new window.FormData(wishForm);
                    const action = e.target.action;

                    fetch(action, {
                            method: 'POST',
                            body: data,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            const result = await response.json();
                            if (!response.ok) {
                                let errorMsg = result.message || 'Terjadi kesalahan saat mengirim ucapan.';
                                if (result.errors) {
                                    errorMsg = Object.values(result.errors).flat().join('<br>');
                                }
                                throw new Error(errorMsg);
                            }
                            return result;
                        })
                        .then(result => {
                            if (result.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terkirim!',
                                    text: result.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                                wishForm.reset();

                                // Prepend new wish to the feed dynamically
                                const feed = document.getElementById('wishes-feed');
                                const emptyAlert = document.getElementById('empty-wishes-alert');
                                if (emptyAlert) {
                                    emptyAlert.remove();
                                }

                                const badgeClass = result.data.kehadiran === 'Hadir' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle';
                                const cardHtml = `
                                    <div class="wish-card" style="opacity: 0; transform: translateY(-10px); transition: all 0.5s ease;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">${result.data.nama}</h5>
                                            <span class="badge ${badgeClass}" style="font-size: 0.75rem; padding: 0.25em 0.6em;">
                                                ${result.data.kehadiran}
                                            </span>
                                        </div>
                                        <p class="mb-1" style="font-size: 0.95rem; line-height: 1.5; color: #444;">${result.data.ucapan}</p>
                                        <div class="text-end">
                                            <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>${result.data.created_at}</small>
                                        </div>
                                    </div>
                                `;
                                feed.insertAdjacentHTML('afterbegin', cardHtml);

                                // Trigger transition animation
                                const card = feed.firstElementChild;
                                setTimeout(() => {
                                    card.style.opacity = '1';
                                    card.style.transform = 'translateY(0)';
                                }, 50);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: error.message || 'Terjadi kesalahan saat mengirim ucapan.',
                                confirmButtonColor: '#3e4b6d'
                            });
                        })
                        .finally(() => {
                            if (window.hideGlobalPreloader) {
                                window.hideGlobalPreloader();
                            }
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            }
                        });
                });
            }
        });
    </script>

    <!-- URL query parameters mapping to recipient info -->
    <script>
        const urlParams = new window.URLSearchParams(window.location.search);
        const nama = urlParams.get('to') || urlParams.get('n') || '';
        const pronoun = urlParams.get('to') ? 'Yth. Bapak/Ibu' : (urlParams.get('p') ? `Yth. ${urlParams.get('p')}` : 'Yth. Bapak/Ibu/Saudara/i');

        const guestNameContainer = document.querySelector('#guest-name-container span');
        const pronounContainer = document.querySelector('#pronoun-container');

        if (nama) {
            if (guestNameContainer) guestNameContainer.innerText = nama.trim();
            if (pronounContainer) pronounContainer.innerText = pronoun.trim();
        } else {
            if (guestNameContainer) guestNameContainer.innerText = '';
            if (pronounContainer) pronounContainer.innerText = 'Bapak/Ibu/Saudara/i';
        }

        const inputNama = document.querySelector('#nama');
        if (inputNama && nama) {
            inputNama.value = nama;
        }

        const inputWishNama = document.querySelector('#wish_nama');
        if (inputWishNama && nama) {
            inputWishNama.value = nama;
        }
    </script>
</body>

</html>