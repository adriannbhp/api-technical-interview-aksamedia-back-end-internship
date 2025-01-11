<?php

namespace App\Services;

use App\Models\Nilai;

class NilaiService
{
    // Method to transform nilaiRT data
    public function transformNilaiRTData($nilaiRTData)
    {
        return $nilaiRTData->map(function ($items, $key) {
            return [
                'nama' => $key,
                'nisn' => $items->first()->nisn,
                'nilaiRt' => $items->mapWithKeys(function ($item) {
                    return [$item->nama_pelajaran => $item->skor];
                }),
            ];
        });
    }

    // Method to transform nilaiST data
    public function transformNilaiSTData($nilaiSTData)
    {
        return $nilaiSTData->map(function ($items) {
            return [
                'nama' => $items->first()->nama,
                'nisn' => $items->first()->nisn,
                'listNilai' => $items->pluck('nilai', 'nama_pelajaran')->mapWithKeys(function ($value, $key) {
                    return [$key => round($value, 2)];
                }),
                'total' => round($items->sum('nilai'), 2),
            ];
        });
    }

    // Method to return the SQL CASE statements for nilaiST calculation
    public function nilaiSTCaseStatements()
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
