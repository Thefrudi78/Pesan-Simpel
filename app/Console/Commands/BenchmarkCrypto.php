<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DiffieHellmanService;

class BenchmarkCrypto extends Command
{
    protected $signature = 'benchmark:crypto';
    protected $description = 'Ukur kecepatan enkripsi dan dekripsi AES-256';

    public function handle(DiffieHellmanService $service)
    {
        $message = "Pesan rahasia untuk benchmark. Panjangnya dibuat 50 karakter agar realistis.";
        $aesKey = random_bytes(32); // simulasi shared secret
        $iterations = 1000;

        // --- Enkripsi ---
        $start = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $encrypted = $service->encrypt($message, $aesKey);
        }
        $end = microtime(true);
        $avgEnc = (($end - $start) / $iterations) * 1000; // ms

        // --- Dekripsi ---
        // Ambil satu sample untuk didekripsi berulang
        $sampleEncrypted = $service->encrypt($message, $aesKey);
        $start = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $service->decrypt($sampleEncrypted['content'], $aesKey, $sampleEncrypted['iv']);
        }
        $end = microtime(true);
        $avgDec = (($end - $start) / $iterations) * 1000;

        $this->info("=== Hasil Benchmark (rata-rata dari {$iterations} iterasi) ===");
        $this->line("🔐 Enkripsi: <fg=yellow>{$avgEnc} ms</> per pesan");
        $this->line("🔓 Dekripsi: <fg=yellow>{$avgDec} ms</> per pesan");
    }
}