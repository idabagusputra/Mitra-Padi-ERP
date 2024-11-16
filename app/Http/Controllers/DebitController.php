<?php

namespace App\Http\Controllers;

use App\Models\Debit;
use App\Models\Kredit;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitController extends Controller
{
    public function index(Request $request)
    {
        $query = Debit::with('petani');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('petani', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhere('keterangan', 'like', "%{$search}%");
        }

        $sort = $request->input('sort', 'desc'); 

$query->orderBy('tanggal', $sort) // Urutkan berdasarkan tanggal
      ->orderBy('id', $sort); // Urutkan berdasarkan id untuk menangani data dengan tanggal yang sama


        $debits = $query->paginate(20);

        $petanisWithOutstandingKredits = Petani::whereHas('kredits', function ($query) {
            $query->where('status', false);
        })->with(['kredits' => function ($query) {
            $query->where('status', false);
        }])->get()->map(function ($petani) {
            $petani->total_hutang = $petani->kredits->sum('jumlah');
            return $petani;
        });

        return view('laravel-examples/debit', compact('debits', 'petanisWithOutstandingKredits'));
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $petanis = Petani::where('nama', 'like', "%{$term}%")
            ->limit(10)
            ->get()
            ->map(function ($petani) {
                return [
                    'id' => $petani->id,
                    'value' => $petani->nama,
                    'label' => $petani->nama
                ];
            });

        return response()->json($petanis);
    }

    public function searchPetani(Request $request)
    {
        $term = $request->get('term');

        $petanis = Petani::where('nama', 'LIKE', '%' . $term . '%')
            ->select('id', 'nama')
            ->with(['kredits' => function ($query) {
                $query->select('petani_id')
                    ->selectRaw('SUM(jumlah * (1 + bunga/100) - COALESCE((SELECT SUM(jumlah) FROM pembayaran_kredit WHERE kredit_id = kredits.id), 0)) as total_hutang')
                    ->groupBy('petani_id');
            }])
            ->get()
            ->map(function ($petani) {
                return [
                    'id' => $petani->id,
                    'nama' => $petani->nama,
                    'total_hutang' => $petani->kredits->sum('total_hutang') ?? 0
                ];
            });

        return response()->json($petanis);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'petani_id' => 'required|exists:petanis,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'bunga' => 'required|numeric|min:0|max:100',
            'keterangan' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $debit = Debit::create($validatedData);
            $debit->processPayment();

            DB::commit();
            return redirect()->back()->with('success', 'Debit entry created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating debit entry: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Debit $debit)
    {
        try {
            $debit->delete();
            return redirect()->route('debit.index')->with('success', 'Debit entry deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('debit.index')->with('error', 'Error deleting debit entry: ' . $e->getMessage());
        }
    }

    public function getTotalHutang($petaniId)
    {
        try {
            $petani = Petani::findOrFail($petaniId);
            $totalHutang = $petani->kredits()->where('status', false)->sum('jumlah');
            return response()->json(['total_hutang' => $totalHutang]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching total hutang: ' . $e->getMessage()], 500);
        }
    }
}
