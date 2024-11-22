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

        $search = $request->input('search');

        // Apply filters
        if ($search) {
            $query->whereHas('petani', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
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

    public function searchPetani(Request $request)
    {
        $term = $request->input('term');
        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->get(['id', 'nama', 'alamat']);

        return response()->json($petanis);
    }

    public function search(Request $request)
    {
        $term = $request->query('term');

        $petanis = Petani::where('nama', 'LIKE', "%{$term}%")
            ->orWhere('alamat', 'LIKE', "%{$term}%")
            ->select('id', 'nama', 'alamat')
            ->get();

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
