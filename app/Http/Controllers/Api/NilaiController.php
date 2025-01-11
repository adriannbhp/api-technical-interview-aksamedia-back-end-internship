<?php

namespace App\Http\Controllers\Api;

use App\Constants\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function nilaiRT()
    {
        // Fetch data for nilaiRT
        $nilaiRT = Nilai::select('nama', 'nisn', 'skor', 'nama_pelajaran')
            ->where('materi_uji_id', 7)
            ->where('nama_pelajaran', '!=', 'Pelajaran Khusus')
            ->get()
            ->groupBy('nama');

        // Transform data to match the desired structure
        $data = $nilaiRT->map(function ($items, $key) {
            return [
                'nama' => $key,  // Keep the 'nama' as is
                'nisn' => $items->first()->nisn,  // Keep the 'nisn' as is
                'nilaiRt' => $items->mapWithKeys(function ($item) {
                    return [strtolower($item->nama_pelajaran) => $item->skor]; // Convert 'nama_pelajaran' to lowercase
                }),
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'message' => ResponseMessages::SUCCESS_NILAI_RT_RETRIEVED,
            'data' => $data,
        ]);
    }

    public function nilaiST()
    {
        // Fetch and calculate data for nilaiST
        $nilaiST = Nilai::query()
            ->select('nama', 'nisn', 'pelajaran_id', 'skor', 'nama_pelajaran')
            ->where('materi_uji_id', 4)
            ->selectRaw("CASE
            WHEN pelajaran_id = 44 THEN skor * 41.67
            WHEN pelajaran_id = 45 THEN skor * 29.67
            WHEN pelajaran_id = 46 THEN skor * 100
            WHEN pelajaran_id = 47 THEN skor * 23.81
            ELSE 0
        END AS nilai")
            ->get()
            ->groupBy('nama')
            ->map(function ($items) {
                return [
                    'nama' => $items->first()->nama,  // Keep the 'nama' as is
                    'nisn' => $items->first()->nisn,  // Keep the 'nisn' as is
                    'listNilai' => $items->pluck('nilai', 'nama_pelajaran')->mapWithKeys(function ($value, $key) {
                        return [strtolower($key) => round($value, 2)]; // Convert 'nama_pelajaran' to lowercase
                    }),
                    'total' => round($items->sum('nilai'), 2), // Total score calculated in SQL
                ];
            });

        // Sort data by total score in descending order
        $sortedNilaiST = $nilaiST->sortByDesc('total')->values();

        return response()->json([
            'status' => 'success',
            'message' => 'Data nilai ST berhasil diambil.',
            'data' => $sortedNilaiST,
        ]);
    }
}
