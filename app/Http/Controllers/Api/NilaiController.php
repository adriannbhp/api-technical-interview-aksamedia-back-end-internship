<?php

namespace App\Http\Controllers\Api;

use App\Constants\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DTOs\Response\GlobalResponse;
use App\Services\NilaiService;  // Import the service class
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    protected $nilaiService;

    // Constructor to inject the NilaiService
    public function __construct(NilaiService $nilaiService)
    {
        $this->nilaiService = $nilaiService;
    }

    // Method to retrieve nilaiRT data
    public function nilaiRT()
    {
        // Fetch data for nilaiRT with a single query, excluding 'Pelajaran Khusus'
        $nilaiRTData = Nilai::where('materi_uji_id', 7)
            ->where('nama_pelajaran', '!=', 'Pelajaran Khusus')
            ->select('nama', 'nisn', 'skor', 'nama_pelajaran')
            ->get()
            ->groupBy('nama');

        // Transform the fetched data into the desired format using the service
        $transformedData = $this->nilaiService->transformNilaiRTData($nilaiRTData);

        // Create DTO response
        $response = new GlobalResponse(
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
            ->selectRaw($this->nilaiService->nilaiSTCaseStatements())
            ->get()
            ->groupBy('nama');

        // Transform the fetched data into the desired format using the service
        $transformedData = $this->nilaiService->transformNilaiSTData($nilaiSTData);

        // Sort the data by total score in descending order
        $sortedData = $transformedData->sortByDesc('total')->values();

        // Create DTO response
        $response = new GlobalResponse(
            'success',
            ResponseMessages::SUCCESS_NILAI_ST_RETRIEVED,
            $sortedData
        );

        return response()->json($response->toArray());
    }
}
