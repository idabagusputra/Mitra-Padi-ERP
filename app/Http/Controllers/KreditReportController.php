<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class KreditReportController extends Controller
{
    public function generatePdf(Request $request)
    {
        // Ambil parameter sort dari request
        $sortOrder = $request->input('sort', 'desc');

        $allKredits = Kredit::with('petani')->get();
        $now = Carbon::now();

        $calculatedKredits = $allKredits->map(function ($kredit) use ($now) {
            $kreditDate = Carbon::parse($kredit->tanggal);
            $selisihBulan = $kreditDate->diffInMonths($now);
            $bunga = $kredit->jumlah * 0.02 * $selisihBulan;
            $hutangPlusBunga = $kredit->jumlah + $bunga;

            $kredit->setAttribute('hutang_plus_bunga', $hutangPlusBunga);
            $kredit->setAttribute('lama_bulan', $selisihBulan);
            $kredit->setAttribute('bunga', $bunga);

            return $kredit;
        });

        // Urutkan data sesuai sortOrder
        $sortedKredits = $calculatedKredits->sortBy(function ($item) {
            return [$item->tanggal, $item->id];
        }, SORT_REGULAR, $sortOrder === 'desc');

        // Hitung ringkasan data
        $kreditsBelumLunas = $calculatedKredits->where('status', 0);
        $jumlahPetaniBelumLunas = $kreditsBelumLunas->pluck('petani_id')->unique()->count();
        $totalKreditBelumLunas = $kreditsBelumLunas->sum('jumlah');
        $totalKreditPlusBungaBelumLunas = $kreditsBelumLunas->sum('hutang_plus_bunga');

        // Render HTML menggunakan Blade
        $html = View::make('kreditReport', [
            'kredits' => $sortedKredits,
            'jumlahPetaniBelumLunas' => $jumlahPetaniBelumLunas,
            'totalKreditBelumLunas' => $totalKreditBelumLunas,
            'totalKreditPlusBungaBelumLunas' => $totalKreditPlusBungaBelumLunas
        ])->render();

        // Generate PDF dengan Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF ke browser
        return $dompdf->stream('Laporan_Kredit_' . date('Y-m-d') . '.pdf');
    }
}
