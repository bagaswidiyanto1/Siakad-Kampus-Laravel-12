<div class="text-center py-8">
    <div class="max-w-md mx-auto bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-sm font-semibold opacity-75">KARTU TANDA MAHASISWA</h3>
                <h2 class="text-xl font-bold mt-1">SIAKAD KAMPUS</h2>
            </div>
            <div class="text-right">
                <p class="text-xs opacity-75">KTM</p>
                <p class="text-xs font-mono">{{ $mahasiswa->nim ?? '********' }}</p>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-6">
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center text-4xl font-bold">
                {{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}
            </div>
            <div class="flex-1 text-left">
                <p class="text-lg font-bold">{{ $mahasiswa->user->name ?? 'Nama Mahasiswa' }}</p>
                <p class="text-sm opacity-90">{{ $mahasiswa->prodi->nama ?? 'Program Studi' }}</p>
                <p class="text-xs opacity-75">{{ $mahasiswa->fakultas->nama ?? 'Fakultas' }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-2 text-xs">
            <div>
                <p class="opacity-75">Angkatan</p>
                <p class="font-semibold">{{ $mahasiswa->angkatan ?? '-' }}</p>
            </div>
            <div>
                <p class="opacity-75">Status</p>
                <p class="font-semibold capitalize">{{ $mahasiswa->status_mahasiswa ?? 'Aktif' }}</p>
            </div>
            <div>
                <p class="opacity-75">Dosen Wali</p>
                <p class="font-semibold text-sm">{{ $mahasiswa->dosenWali->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="opacity-75">Tgl Masuk</p>
                <p class="font-semibold">{{ $mahasiswa->tgl_masuk ? $mahasiswa->tgl_masuk->format('d/m/Y') : '-' }}</p>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-white/20 text-center text-xs opacity-75">
            <p>KTM ini adalah bukti identitas resmi mahasiswa SIAKAD Kampus</p>
            <p class="mt-1">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-6 text-gray-500 text-sm">
        <p class="font-semibold">⚠️ Informasi</p>
        <p>KTM akan terisi otomatis berdasarkan data biodata mahasiswa.</p>
        <p class="text-xs mt-1">* Fitur cetak dan photo akan hadir di update berikutnya</p>
    </div>
</div>
