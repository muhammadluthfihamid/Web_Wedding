<?php

namespace App\Http\Controllers;

use App\Models\BiodataPria;
use App\Models\BiodataWanita;
use App\Models\Gallery;
use App\Models\Gifts;
use App\Models\Info;
use App\Models\Story;
use App\Models\TurutMengundang;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Halaman utama publik (tampilkan data demo milik superadmin saja).
     */
    public function index(Request $request)
    {
        $theme = null;
        if ($request->filled('theme')) {
            $theme = \App\Models\Theme::where('slug', $request->query('theme'))->first();
        }

        $eventType = $request->query('event_type', $theme?->category ?? 'wedding');

        // Ambil superadmin (pemilik data demo yang diinput di admin panel)
        $demoUser = null;
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())) {
            $demoUser = Auth::user();
        } else {
            $demoUser = User::where('role', 'superadmin')->first()
                ?? User::where('event_type', $eventType)->first()
                ?? User::first();
        }

        $demoUserId = $demoUser?->id;

        // Utamakan data milik demoUser (superadmin/admin)
        $infos            = $demoUserId ? Info::where('user_id', $demoUserId)->get() : collect();
        $biodataPria      = $demoUserId ? BiodataPria::where('user_id', $demoUserId)->get() : collect();
        $biodataWanita    = $demoUserId ? BiodataWanita::where('user_id', $demoUserId)->get() : collect();
        $gifts            = $demoUserId ? Gifts::where('user_id', $demoUserId)->get() : collect();
        $story            = $demoUserId ? Story::where('user_id', $demoUserId)->orderByDesc('created_at')->first() : null;
        $galeries         = $demoUserId ? Gallery::where('user_id', $demoUserId)->get() : collect();
        $wishes           = Wish::query()->orderByDesc('created_at')->get();
        $turutMengundangs = $demoUserId ? TurutMengundang::where('user_id', $demoUserId)->orderBy('urutan', 'asc')->orderBy('created_at', 'asc')->get() : collect();

        // Fallback jika belum ada data khusus demoUser
        if ($infos->isEmpty()) {
            $infos = Info::all();
        }
        if ($biodataPria->isEmpty()) {
            $biodataPria = BiodataPria::all();
        }
        if ($biodataWanita->isEmpty()) {
            $biodataWanita = BiodataWanita::all();
        }
        if ($gifts->isEmpty()) {
            $gifts = Gifts::all();
        }
        if (!$story) {
            $story = Story::query()->orderByDesc('created_at')->first();
        }
        if ($galeries->isEmpty()) {
            $galeries = Gallery::all();
        }
        if ($turutMengundangs->isEmpty()) {
            $turutMengundangs = TurutMengundang::query()->orderBy('urutan', 'asc')->orderBy('created_at', 'asc')->get();
        }

        $viewName = 'front-end.master';

        if ($theme && view()->exists($theme->blade_path)) {
            $viewName = $theme->blade_path;
        } elseif ($eventType === 'khitanan') {
            $viewName = 'themes.khitanan.islamic';
        }

        return view($viewName, compact(
            'infos', 'biodataPria', 'biodataWanita',
            'gifts', 'story', 'galeries', 'wishes', 'turutMengundangs'
        ));
    }

    /**
     * Halaman undangan per penyewa berdasarkan slug.
     */
    public function undangan(string $slug)
    {
        /** @var User $tenant */
        $tenant = User::query()->where('slug', $slug)->with('theme')->firstOrFail();

        $infos            = Info::query()->where('user_id', $tenant->id)->get();
        $biodataPria      = BiodataPria::query()->where('user_id', $tenant->id)->get();
        $biodataWanita    = BiodataWanita::query()->where('user_id', $tenant->id)->get();
        $gifts            = Gifts::query()->where('user_id', $tenant->id)->get();
        $story            = Story::query()->where('user_id', $tenant->id)->orderByDesc('created_at')->first();
        $galeries         = Gallery::query()->where('user_id', $tenant->id)->get();
        $wishes           = Wish::query()->orderByDesc('created_at')->get();
        $turutMengundangs = TurutMengundang::query()->where('user_id', $tenant->id)->orderBy('urutan', 'asc')->orderBy('created_at', 'asc')->get();

        // Tentukan view berdasarkan tema & jenis acara tenant
        $viewName = 'front-end.master';
        if ($tenant->theme && view()->exists($tenant->theme->blade_path)) {
            $viewName = $tenant->theme->blade_path;
        } elseif ($tenant->isKhitanan()) {
            $viewName = 'themes.khitanan.islamic';
        }

        return view($viewName, compact(
            'infos', 'biodataPria', 'biodataWanita',
            'gifts', 'story', 'galeries', 'wishes', 'tenant', 'turutMengundangs'
        ));
    }
}
