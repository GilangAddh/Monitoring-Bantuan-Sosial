<div>
    <div class="navbar bg-base-100 px-4 shadow-md">
        <div class="flex-1 space-x-3">
            <label for="my-drawer" class="drawer-button cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </label>
            <img src="{{ asset('images/logo.png') }}" class="w-[100px] hidden md:flex">
        </div>

        <div class="flex-none space-x-5">
            <div class="indicator">
                <label for="my-drawer-4" class="drawer-button cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </label>
                <span class="badge badge-sm indicator-item text-xs bg-white">0</span>
            </div>

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="flex items-center space-x-2 bg-gray-100 pr-4 rounded-full">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="{{ asset('images/avatar.png') }}" />
                        </div>
                    </div>
                    <p class="text-sm">{{ $profileName }}</p>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a wire:navigate href="/user/profile">Profil</a></li>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <li><a wire:navigate href="/logout" @click.prevent="$root.submit();">Logout</a>
                        </li>
                    </form>
                </ul>
            </div>
        </div>
    </div>

    <div class="drawer z-10">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-side">
            <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-white text-base-content min-h-full w-80 p-4">
                <img src="{{ asset('images/logo.png') }}" class="w-[200px] h-auto mx-auto my-4">

                <div class="bg-[#60c0d0] text-white uppercase py-1 px-4 rounded-xl text-[13px] mx-auto mb-4">
                    {{ $profileName }}
                </div>
                <!-- Sidebar content here -->
                <li
                    class="{{ Request::is('dashboard') ? 'bg-[#60c0d0] text-white' : '' }} hover:bg-[#60c0d0] hover:text-white rounded-md transition duration-200 my-1">
                    <a href="/dashboard" class="flex items-center space-x-2 py-2 px-4">
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (auth()->user()->hasRole('daerah'))
                    <li
                        class="{{ Request::is('daftar-laporan') ? 'bg-[#60c0d0] text-white' : '' }} hover:bg-[#60c0d0] hover:text-white rounded-md transition duration-200 my-1">
                        <a href="/daftar-laporan" class="flex items-center space-x-2 py-2 px-4">
                            <span>Daftar Laporan</span>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasRole('admin'))
                    <li
                        class="{{ Request::is('verifikasi-laporan') ? 'bg-[#60c0d0] text-white' : '' }} hover:bg-[#60c0d0] hover:text-white rounded-md transition duration-200 my-1">
                        <a href="/verifikasi-laporan" class="flex items-center space-x-2 py-2 px-4">
                            <span>Verifikasi Laporan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="drawer drawer-end z-10">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-side">
            <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-white text-base-content min-h-full w-80 p-4">
                <li><a>Belum Ada Notifikasi</a></li>
            </ul>
        </div>
    </div>
</div>
