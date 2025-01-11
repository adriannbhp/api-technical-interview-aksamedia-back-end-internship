<?php

namespace App\Http\Controllers\Api;

use App\Constants\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DTOs\Response\NilaiResponse;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    // Method to retrieve nilaiRT data
    public function nilaiRT()
    {
        // Fetch data for nilaiRT with a single query, excluding 'Pelajaran Khusus'
        $nilaiRTData = Nilai::where('materi_uji_id', 7)
            ->where('nama_pelajaran', '!=', 'Pelajaran Khusus')
            ->select('nama', 'nisn', 'skor', 'nama_pelajaran')
            ->get()
            ->groupBy('nama'); // Group data by 'nama' after retrieving from the database

        // Transform the fetched data into the desired format
        $transformedData = $this->transformNilaiRTData($nilaiRTData);

        // Create DTO response
        $response = new NilaiResponse(
            'success',
            ResponseMessages::SUCCESS_NILAI_RT_RETRIEVED,
            $transformedData
        );

        return response()->json($response->toArray());
    }

    // Method to retrieve nilaiST data
    public function nilaiST()
    {
        // Fetch and calculate nilaiST data with inline SQL logic
        $nilaiSTData = Nilai::where('materi_uji_id', 4)
            ->select('nama', 'nisn', 'pelajaran_id', 'skor', 'nama_pelajaran')
            ->selectRaw($this->nilaiSTCaseStatements())
            ->get()
            ->groupBy('nama'); // Group data by 'nama' after fetching from the database

        // Transform the fetched data into the desired format
        $transformedData = $this->transformNilaiSTData($nilaiSTData);

        // Sort the data by total score in descending order
        $sortedData = $transformedData->sortByDesc('total')->values();

        // Create DTO response
        $response = new NilaiResponse(
            'success',
            ResponseMessages::SUCCESS_NILAI_ST_RETRIEVED,
            $sortedData
        );

        return response()->json($response->toArray());
    }

    // Helper method to transform nilaiRT data
    private function transformNilaiRTData($nilaiRTData)
    {
        return $nilaiRTData->map(function ($items, $key) {
            return [
                'nama' => $key,
                'nisn' => $items->first()->nisn,
                'nilaiRt' => $items->mapWithKeys(function ($item) {
                    return [$item->nama_pelajaran => $item->skor]; // Map 'nama_pelajaran' to 'skor'
                }),
            ];
        });
    }

    // Helper method to transform nilaiST data
    private function transformNilaiSTData($nilaiSTData)
    {
        return $nilaiSTData->map(function ($items) {
            return [
                'nama' => $items->first()->nama,  // Keep 'nama' as is
                'nisn' => $items->first()->nisn,  // Keep 'nisn' as is
                'listNilai' => $items->pluck('nilai', 'nama_pelajaran')->mapWithKeys(function ($value, $key) {
                    return [$key => round($value, 2)]; // Round the values of 'nilai'
                }),
                'total' => round($items->sum('nilai'), 2), // Calculate total score for the group
            ];
        });
    }

    // Helper method to return SQL CASE statements for nilaiST calculation
    private function nilaiSTCaseStatements()
    {
        return "
            CASE
                WHEN pelajaran_id = 44 THEN skor * 41.67
                WHEN pelajaran_id = 45 THEN skor * 29.67
                WHEN pelajaran_id = 46 THEN skor * 100
                WHEN pelajaran_id = 47 THEN skor * 23.81
                ELSE 0
            END AS nilai
        ";
    }
}
