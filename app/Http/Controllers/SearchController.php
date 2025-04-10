<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    // Menambahkan middleware autentikasi agar hanya user yang login yang bisa mengakses API ini
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Menambahkan middleware auth: sanctum
    }

    // Fungsi untuk mengambil data dari URL eksternal
    // Fungsi untuk mengambil data dari URL eksternal
    public function fetchData()
    {
        $response = Http::get('https://ogienurdiana.com/career/ecc694ce4e7f6e45a5a7912cde9fe131');

        if ($response->successful()) {
            // Mengambil data yang ada dalam "DATA"
            $data = $response->json()['DATA'];

            // Parse data yang dipisahkan oleh baris dan pipe "|"
            $lines = explode("\n", $data);
            $parsedData = [];
            foreach ($lines as $line) {
                $columns = explode('|', $line);
                if (count($columns) === 3) {  // Pastikan ada 3 kolom (YMD, NIM, NAMA)
                    $parsedData[] = [
                        'ymd' => $columns[0],
                        'nim' => $columns[1],
                        'name' => $columns[2],
                    ];
                }
            }

            return $parsedData;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'data' => null
            ], 500);
        }
    }

    // Fungsi untuk pencarian berdasarkan NAMA, NIM, atau YMD
    public function search(Request $request)
    {
        // Ambil parameter pencarian
        $query = $request->query('q');  // Parameter pencarian (q)

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query parameter "q" is required.',
                'data' => null
            ], 400);
        }

        // Ambil data dari fetchData function
        $data = $this->fetchData();

        // Filter data berdasarkan pencarian (NIM, NAMA, atau YMD)
        $filteredData = array_filter($data, function ($entry) use ($query) {
            return strpos(strtolower($entry['name']), strtolower($query)) !== false ||
                   strpos($entry['nim'], $query) !== false ||
                   strpos($entry['ymd'], $query) !== false;
        });

        // Kembalikan hasil pencarian
        return response()->json([
            'status' => 'success',
            'message' => 'Search results found.',
            'data' => array_values($filteredData)  // Reindex array untuk menghindari key yang hilang
        ]);
    }
}
