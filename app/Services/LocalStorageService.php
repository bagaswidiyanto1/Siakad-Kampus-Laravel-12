<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;

class LocalStorageService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('public');
    }

    /**
     * Upload file ke storage lokal.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $path      Folder tujuan relatif terhadap disk (mis. "mahasiswa/2024001")
     * @param  string  $filename  Nama file final (dengan ekstensi)
     * @param  bool    $compress  Aktifkan resize + kompresi untuk file gambar
     * @return string|null        Path relatif yang disimpan (mis. "mahasiswa/2024001/mhs_xxx.jpg"), atau null kalau gagal
     */
    public function upload($file, $path, $filename, $compress = true)
    {
        try {
            $fullPath = trim($path, '/') . '/' . $filename;

            $isImage = in_array($file->getMimeType(), [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/avif',
            ]);

            if ($compress && $isImage) {
                // Baca file gambar
                $image = Image::read($file->getPathname());

                // Scale proporsional: sisi terpanjang maksimal 500px
                // Kalau gambar lebih kecil dari 500px, tetap di-scale ke max 500 tanpa memperbesar
                $image->scaleDown(width: 500, height: 500);

                // Pilih encoder berdasarkan ekstensi asli file
                $ext = strtolower($file->getClientOriginalExtension());

                if (in_array($ext, ['webp'])) {
                    $encoded = $image->encode(new WebpEncoder(quality: 80));
                } else {
                    // Default: JPEG (untuk jpg/jpeg/png/gif → kompresi lebih kecil)
                    $encoded = $image->encode(new JpegEncoder(quality: 80));
                }

                $this->disk->put($fullPath, (string) $encoded);
            } else {
                // File non-gambar: simpan apa adanya
                $this->disk->put($fullPath, file_get_contents($file->getPathname()));
            }

            Log::info('Upload successful', ['path' => $fullPath]);

            return $fullPath;
        } catch (\Throwable $e) {
            Log::error('Local storage upload error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Hapus file dari storage lokal.
     * Menerima path relatif (mis. "mahasiswa/2024001/mhs_xxx.jpg")
     * maupun URL lengkap (mis. "http://.../storage/mahasiswa/.../mhs_xxx.jpg").
     */
    public function delete($path)
    {
        try {
            // Normalisasi: kalau yang dikirim URL lengkap, ekstrak path-nya
            if (str_starts_with($path, 'http')) {
                $path = $this->extractPathFromUrl($path) ?? $path;
            }

            $path = ltrim($path, '/');

            if ($this->disk->exists($path)) {
                $this->disk->delete($path);
                Log::info('Delete successful', ['path' => $path]);
                return true;
            }

            Log::warning('File not found', ['path' => $path]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Local storage delete error', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Generate nama file unik.
     */
    public function generateFilename($originalName, $prefix = '')
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return $prefix . '_' . time() . '_' . uniqid() . '.' . $ext;
    }

    /**
     * Ekstrak path relatif dari URL lengkap.
     * Contoh: "http://localhost/storage/mahasiswa/2024001/mhs_xxx.jpg" → "mahasiswa/2024001/mhs_xxx.jpg"
     */
    public function extractPathFromUrl($url)
    {
        $pattern = '/\/storage\/(.*)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Ambil URL publik dari path relatif.
     * Contoh: "mahasiswa/2024001/mhs_xxx.jpg" → "http://localhost:8000/storage/mahasiswa/2024001/mhs_xxx.jpg"
     */
    public function url($path)
    {
        return $this->disk->url($path);
    }
}
