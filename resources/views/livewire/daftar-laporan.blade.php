<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Daftar Laporan</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="#">Index</a>
            </li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Standar Audit</h1>

    <div class="flex justify-between my-6 items-center flex-wrap">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-3/5 md:w-1/4">
            <input type="text" class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full"
                placeholder="Cari" wire:model.live.debounce.400ms="search" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <div class="flex justify-end space-x-4">
            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm"
                wire:click="openModal('tambah')">
                Tambah
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md text-center">
                    <td class="text-center">No</td>
                    <td>Nama Program</td>
                    <td>Jumlah Penerima</td>
                    <td>Provinsi</td>
                    <td>Kota</td>
                    <td>Bukti Penyaluran</td>
                    <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $index => $item)
                    <tr class="text-center">
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            {{ $item->nama_program }}
                        </td>
                        <td>
                            {{ $item->jumlah_penerima }}
                        </td>
                        <td>
                            {{ $item->provinsi }}
                        </td>
                        <td>
                            {{ $item->kabupaten }}
                        </td>
                        <td>
                            <a href="{{ asset('storage/bukti_penyaluran/' . $item->bukti_penyaluran) }}"
                                class="link link-hover text-blue-500" target="_blank">
                                Download {{ $item->bukti_penyaluran }}
                            </a>
                        </td>

                        <th class="shadow-xl max-w-48">
                            <div class="flex justify-center items-center space-x-2">
                                <button wire:click="openModal('lihat', {{ $item->id }})">
                                    <i class="fas fa-eye text-black"></i>
                                </button>
                                <button wire:click="openModal('edit', {{ $item->id }})">
                                    <i class="fas fa-edit text-black"></i>
                                </button>
                                <button wire:click="openModal('hapus', {{ $item->id }})">
                                    <i class="fas fa-trash text-black"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $laporan->links() }}
    </div>
    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-3xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>


            @if ($modalAction === 'hapus')
                <p>Apakah anda yakin ingin menghapus data ini?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                            wire:click="resetModal">Tutup</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="delete">Ya,
                            hapus</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text md:text-[16px]">Nama Program <span
                                    class="text-red-500">*</span></span>
                        </div>
                        <select {{ $modalAction === 'lihat' ? 'disabled' : '' }} id="program"
                            class="input input-bordered w-full input-md rounded-lg @error('nama_program') border-red-500 @enderror"
                            wire:model.live='nama_program'>
                            <option value="">Pilih Nama Program</option>
                            <option value="PKH">PKH</option>
                            <option value="BLT">BLT</option>
                            <option value="Bansos">Bansos</option>
                            <option value="RTLH">RTLH</option>
                        </select>

                        @error('nama_program')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text md:text-[16px]">Jumlah Penerima <span
                                    class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="number"
                            wire:model="jumlah_penerima" placeholder="Masukkan jumlah penerima"
                            class="input input-bordered w-full input-md @error('jumlah_penerima') border-red-500 @enderror" />

                        @error('jumlah_penerima')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text md:text-[16px]">Pilih Provinsi <span
                                    class="text-red-500">*</span></span>
                        </div>
                        <select id="province" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="input input-bordered w-full input-md rounded-lg @error('selectedProvince') border-red-500 @enderror"
                            wire:model.live='selectedProvince'>
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>

                        @error('selectedProvince')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>
                    @if ($selectedProvince)
                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Pilih Kabupaten/Kota <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <select id="regency" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                                wire:model.live="selectedRegency"
                                class="input input-bordered w-full input-md rounded-lg @error('selectedRegency') border-red-500 @enderror">
                                <option value="">Pilih Kabupaten/Kota</option>
                                @foreach ($regencies as $regency)
                                    <option value="{{ $regency['code'] }}">{{ $regency['name'] }}</option>
                                @endforeach
                            </select>

                            @error('selectedRegency')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif
                    @if ($selectedRegency)
                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Pilih Kecamatan <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <select id="district" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                                wire:model="selectedDistrict"
                                class="input input-bordered w-full input-md rounded-lg @error('selectedDistrict') border-red-500 @enderror">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district['code'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>

                            @error('selectedDistrict')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text md:text-[16px]">Bukti Penyaluran <span
                                    class="text-red-500">*</span></span>
                        </div>
                        @if ($modalAction != 'tambah')
                            <a href="{{ asset('storage/bukti_penyaluran/' . $old_bukti_penyaluran) }}"
                                class="link link-hover text-blue-500" target="_blank">
                                Download {{ $old_bukti_penyaluran }}
                            </a>
                        @endif

                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="file"
                            wire:model="bukti_penyaluran"
                            class="file-input file-input-bordered w-full @error('bukti_penyaluran') border-red-500 @enderror" />
                        @if ($bukti_penyaluran)
                            <a class="link link-hover underline hover:text-[#60c0d0]"
                                href="{{ $bukti_penyaluran->temporaryUrl() }}" target="_blank">
                                <span class="ml-2">
                                    download {{ $bukti_penyaluran->getClientOriginalName() }}
                                </span>
                            </a>
                        @endif
                        @error('bukti_penyaluran')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text md:text-[16px]">Catatan Tambahan</span>
                        </div>
                        <textarea {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="textarea textarea-bordered w-full @error('catatan') border-red-500 @enderror" rows="3"
                            wire:model='catatan'></textarea>

                        @error('catatan')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>
                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                                wire:click="resetModal">Tutup</button>

                            @if ($modalAction != 'lihat')
                                <button type="submit"
                                    class="btn btn-sm bg-[#60c0d0] text-white">{{ $modalAction === 'edit' ? 'Simpan' : 'Tambah' }}</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif

        </div>
    </dialog>
</div>
