@extends('layouts.user_type.auth')

@section('content')

<style>
    #search-results {
        position: absolute;
        background-color: white;
        border: 1px solid #ddd;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
    }

    #search-results .dropdown-item {
        padding: 10px;
        cursor: pointer;
    }

    #search-results .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Styling untuk modal dan area PDF */
    .modal .modal-dialog {
        max-width: 450px;
    }

    .modal-body {
        position: relative;
        padding: 15px;
    }

    .pdf-viewer {
        width: 100%;
        height: 600px;
        border: none;
    }

    .form-control {
        height: 40px;
    }

    .btn {
        height: 40px;
        line-height: 1;
        /* Agar ikon pencarian tetap berada di tengah */
    }
</style>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-3 p-3">
                    <div class="d-flex flex-row justify-content-between align-items-center" style="height: 40,4px;">
                        <div>
                            <h5 class="mb-0 mx-2">Daftar Giling JI</h5>
                        </div>
                        <div class="d-flex align-items-center">
                            <form method="GET" action="{{ route('daftar-giling.index') }}" class="d-flex align-items-center">
                                <div class="me-3" style="width: 250px; position: relative;">
                                    <div class="input-group">
                                        <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari daftar giling..." aria-label="Cari daftar giling" value="{{ request('search') }}" autocomplete="off">
                                        <button class="btn btn-outline-primary mb-0" type="submit" aria-label="Cari">
                                            <i class="fas fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <!-- Dropdown menu for search results -->
                                    <div id="search-results" class="dropdown-menu w-100 p-0" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000; max-height: 200px; overflow-y: auto;">
                                        <!-- Hasil pencarian akan di-render di sini -->
                                    </div>
                                </div>
                                <div class="me-3" style="width: 150px;">
                                    <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </div>
                                <a href="mitrapadi.com/giling" class="btn bg-gradient-primary mb-0 d-flex align-items-center">
                                    <i class="fas fa-plus me-2"></i>
                                    <span>New Giling</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pt-3 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                    <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Petani</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Tanggal</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Giling</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Beras Jual</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Harga Jual</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Sisa Dana</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Buruh Giling</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Buruh Jemur</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Jual Konga</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Jual Menir</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Bunga Hutang</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Pengambilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarGilings as $daftarGiling)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->id }}</p>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-link text-dark px-2 mb-0 view-pdf-btn" data-id="{{ $daftarGiling->id }}">
                                            <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                            View
                                        </a>
                                        <form action="{{ route('daftar-giling.destroy', $daftarGiling->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-2 mb-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <i class="far fa-trash-alt me-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->giling->petani->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $daftarGiling->created_at->format('Y-m-d') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->giling_kotor, 2, ',', '.') }} Kg</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->beras_jual, 2, ',', '.') }} Kg</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->harga_jual, 2, ',', '.') }},00</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_penerima, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_biaya_buruh_giling, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_biaya_buruh_jemur, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_jual_konga, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->dana_jual_menir, 2, ',', '.') }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($daftarGiling->bunga) }}%</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($daftarGiling->total_pengambilan, 2, ',', '.') }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">Receipt #</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button id="printPdf" class="btn btn-primary">Print</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center ps-2 mt-3 mx-3">
        <div>
            Showing
            <strong>{{ $daftarGilings->firstItem() }}</strong> to
            <strong>{{ $daftarGilings->lastItem() }}</strong> of
            <strong>{{ $daftarGilings->total() }}</strong> entries
        </div>
        <div>
            {{ $daftarGilings->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            const form = searchInput.closest('form'); // Mengambil elemen form terdekat dari input

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim();

                if (searchTerm.length > 0) {
                    fetch(`/search-daftar-giling?term=${searchTerm}`)
                        .then(response => response.json())
                        .then(data => {
                            searchResults.innerHTML = '';
                            searchResults.style.display = 'block';

                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('dropdown-item');
                                div.textContent = `${item.giling.petani.nama}`;
                                div.addEventListener('click', function() {
                                    searchInput.value = this.textContent;
                                    searchResults.style.display = 'none';

                                    form.submit(); // Submit form setelah item di-klik
                                });
                                searchResults.appendChild(div);
                            });
                        });
                } else {
                    searchResults.style.display = 'none';
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && e.target !== searchResults) {
                    searchResults.style.display = 'none';
                }
            });

            // Updated event listener for View buttons
            document.querySelectorAll('.view-pdf-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const gilingId = this.getAttribute('data-id');
                    const pdfPath = `/receipts/receipt-${gilingId}.pdf`;

                    // Set src viewer PDF
                    document.getElementById('pdfViewer').src = pdfPath;

                    // Update modal title with the correct ID
                    document.getElementById('pdfModalLabel').textContent = `Receipt #${gilingId}`;

                    // Display the modal
                    const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
                    pdfModal.show();
                });
            });

            // Event listener for Print button
            document.getElementById('printPdf').addEventListener('click', function() {
                const pdfViewer = document.getElementById('pdfViewer').contentWindow;
                pdfViewer.print();
            });
        });
    </script>





    @endsection