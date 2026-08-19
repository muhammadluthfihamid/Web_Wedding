@php
    $userTenant = $tenant ?? (isset($infos) && $infos->first()?->user ? $infos->first()->user : Auth::user());
    $isKhitanan = $userTenant ? $userTenant->isKhitanan() : false;

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

    $guestTo = request('to') ? request('to') : null;

    if ($isKhitanan) {
        $namaAnak = $namaPria ?: ($namaIstri ?: 'Ananda');
        $ogTitle = "Undangan Khitanan " . $namaAnak . ($guestTo ? " - Yth. " . $guestTo : "");
        $ogDescription = "Tanpa mengurangi rasa hormat, kami mengundang anda untuk hadir di acara khitanan kami.";
    } else {
        $namaIstri = $namaIstri ?: 'Mempelai Wanita';
        $namaPria = $namaPria ?: 'Mempelai Pria';
        $coupleTitle = $namaIstri . ' & ' . $namaPria;
        $ogTitle = "Undangan Pernikahan " . $coupleTitle . ($guestTo ? " - Yth. " . $guestTo : "");
        $ogDescription = "Tanpa mengurangi rasa hormat, kami mengundang anda untuk hadir di acara pernikahan kami.";
    }

    // Dynamic Image preview selection:
    $ogImage = null;

    // Check bride photo
    if (isset($biodataWanita) && $biodataWanita->isNotEmpty() && !empty($biodataWanita->first()->foto)) {
        $ogImage = asset('storage/' . $biodataWanita->first()->foto);
    } 
    // Check groom / child photo
    elseif (isset($biodataPria) && $biodataPria->isNotEmpty() && !empty($biodataPria->first()->foto)) {
        $ogImage = asset('storage/' . $biodataPria->first()->foto);
    }

    // Default fallback image
    if (!$ogImage) {
        $ogImage = asset('assets/img/wa-preview.png');
    }

    $imagePath = parse_url($ogImage, PHP_URL_PATH) ?? '';
    $imageExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    $ogImageType = ($imageExtension === 'jpg' || $imageExtension === 'jpeg') ? 'image/jpeg' : 'image/png';
@endphp

<!-- Open Graph / Meta WhatsApp Link Preview -->
<meta property="og:site_name" content="{{ $ogTitle }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->full() }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:secure_url" content="{{ $ogImage }}">
<meta property="og:image:type" content="{{ $ogImageType }}">
<meta property="og:image:width" content="600">
<meta property="og:image:height" content="600">

<link rel="icon" type="{{ $ogImageType }}" href="{{ $ogImage }}">
<link rel="shortcut icon" type="{{ $ogImageType }}" href="{{ $ogImage }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
