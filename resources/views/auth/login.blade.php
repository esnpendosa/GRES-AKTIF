@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center mx-auto shadow-md shadow-teal-500/20">
                <i data-lucide="layers" class="w-6 h-6"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Masuk ke GRES-AKTIF</h1>
            <p class="text-xs text-slate-500">Gresik Asset Activation & Community Intelligence</p>
        </div>

        <!-- 1-Click Demo Accounts Selector -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5 text-xs">
            <span class="font-bold text-slate-700 block uppercase text-[10px] tracking-wider">Akses Cepat Mode Demo:</span>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('auth.demo', 'masyarakat') }}" class="p-2.5 rounded-xl bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-400 text-slate-800 text-center font-bold transition-all">
                    Warga (Komunitas)
                </a>
                <a href="{{ route('auth.demo', 'desa') }}" class="p-2.5 rounded-xl bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-400 text-slate-800 text-center font-bold transition-all">
                    Pemerintah Desa
                </a>
                <a href="{{ route('auth.demo', 'kecamatan') }}" class="p-2.5 rounded-xl bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-400 text-slate-800 text-center font-bold transition-all">
                    Kecamatan
                </a>
                <a href="{{ route('auth.demo', 'kabupaten') }}" class="p-2.5 rounded-xl bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-400 text-slate-800 text-center font-bold transition-all">
                    Kabupaten (Bappeda)
                </a>
            </div>
        </div>

        <!-- Standard Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 text-sm" placeholder="nama@gresaktif.id" />
                @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kata Sandi</label>
                <input type="password" name="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500 text-sm" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-teal-600 focus:ring-teal-500" />
                    <span class="text-slate-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/30 transition-all active:scale-95">
                Masuk ke Aplikasi
            </button>
        </form>

    </div>
</div>
@endsection
