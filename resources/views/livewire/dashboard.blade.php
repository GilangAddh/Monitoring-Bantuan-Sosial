<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Dashboard</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="#">Index</a>
            </li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Dashboard</h1>
    <div class="my-4">
        <div class="rounded-lg border-2 border-[#60C0D0] border-separate p-8 my-4">
            <h1 class="font-bold text-xl">Total Laporan</h1>
            <div class="flex flex-wrap justify-start gap-4">
                <div class="card bg-base-100 w-72 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Seluruh Laporan</h2>
                        <p class="font-bold text-3xl text-[#60C0D0]">{{ $total_laporan }}</p>
                    </div>
                </div>
                <div class="card bg-base-100 w-72 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Program PKH</h2>
                        <p class="font-bold text-3xl text-[#60C0D0]">{{ $total_pkh }}</p>
                    </div>
                </div>
                <div class="card bg-base-100 w-72 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Program BLT</h2>
                        <p class="font-bold text-3xl text-[#60C0D0]">{{ $total_blt }}</p>
                    </div>
                </div>
                <div class="card bg-base-100 w-72 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Program Bansos</h2>
                        <p class="font-bold text-3xl text-[#60C0D0]">{{ $total_bansos }}</p>
                    </div>
                </div>
                <div class="card bg-base-100 w-72 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title">Program RTLH</h2>
                        <p class="font-bold text-3xl text-[#60C0D0]">{{ $total_rtlh }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
