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
    <div>
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-3 p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <!-- Bagian Label -->
                        <h5 class="mb-3 mb-md-0 mx-2">Manajemen Petani</h5>

                        <!-- Bagian Dropdown dan Tombol -->
                        <div class="d-flex flex-wrap gap-2">

                            <div style="width: 150px;">
                                <form method="GET" action="{{ route('petani.index') }}" class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                                    <select name="sort" id="sort-order" class="form-select" onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </form>
                            </div>

                            <div style="width: 150px;">
                                <form method="GET" action="{{ route('petani.index') }}" class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                                    <select name="alamat" id="alamat-filter" class="form-select" onchange="this.form.submit()">
                                        <option value="all">Semua Alamat</option>
                                        <option value="campur" {{ request('alamat') == 'campur' ? 'selected' : '' }}>Campur</option>
                                        @foreach($alamatList as $alamat)
                                        <option value="{{ $alamat }}" {{ request('alamat') == $alamat ? 'selected' : '' }}>{{ $alamat }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Search dan Tombol -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-3">
                        <form method="GET" action="{{ route('petani.index') }}" class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                            <div class="me-2 w-100" style="position: relative;">
                                <div class="input-group">
                                    <input type="text" id="search-input" name="search" class="form-control" placeholder="Cari petani..." aria-label="Cari daftar petani" value="{{ request('search') }}" autocomplete="off">
                                    <button class="btn btn-outline-primary" type="submit" aria-label="Cari">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="dropdown-menu w-100" style="display: none; position: absolute; max-height: 200px; overflow-y: auto; z-index: 1000;">
                                    <!-- Hasil pencarian -->
                                </div>
                            </div>

                            <button class="btn bg-gradient-primary d-flex align-items-center justify-content-center mt-3 mt-md-0" type="button" data-bs-toggle="modal" data-bs-target="#addPetaniModal" style="width: 180px;">
                                <i class=" fas fa-plus me-2"></i>
                                <span>New Petani</span>
                            </button>
                        </form>
                    </div>
                </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">ID</th>
                                    <th class="text-uppercase text-primary font-weight-bolder ps-2" style="font-size: 0.85rem;">Nama</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Alamat</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">No Telepon</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Total Hutang</th>
                                    <th class="text-uppercase text-primary font-weight-bolder text-center" style="font-size: 0.85rem;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($petanis as $petani)
                                <tr>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->nama }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->alamat }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $petani->no_telepon }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($petani->total_hutang, 0, ',', '.') }},00</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="#" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="modal" data-bs-target="#editPetaniModal{{ $petani->id }}">
                                                <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>
                                                Edit
                                            </a>
                                            <!-- <form action="{{ route('petani.destroy', $petani->id) }}" method="POST" data-delete-petani="{{ $petani->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0">
                                                    <i class="fas fa-trash text-danger me-2" aria-hidden="true"></i>
                                                    Delete
                                                </button>
                                            </form> -->
                                        </div>
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
</div>

<!-- Add Petani Modal -->
<div class="modal fade" id="addPetaniModal" tabindex="-1" role="dialog" aria-labelledby="addPetaniModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPetaniModalLabel">Add New Petani</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('petani.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Petani Modal -->
@foreach($petanis as $petani)
<div class="modal fade" id="editPetaniModal{{ $petani->id }}" tabindex="-1" role="dialog" aria-labelledby="editPetaniModalLabel{{ $petani->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="editPetaniModalLabel{{ $petani->id }}">Edit Petani</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPetaniForm{{ $petani->id }}" action="{{ route('petani.update', $petani->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $petani->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $petani->alamat }}" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ $petani->no_telepon }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-gradient-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageDiv = document.getElementById('message');

        // Show message function
        function showMessage(message, type = 'success') {
            if (messageDiv) {
                messageDiv.textContent = message;
                messageDiv.className = `alert alert-${type}`; // Bootstrap alert classes
                messageDiv.style.display = 'block';
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 3000); // Hide message after 3 seconds
            } else {
                console.error("messageDiv is null.");
            }
        }

        // Delete Petani
        document.querySelectorAll('form[data-delete-petani]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const petaniId = this.getAttribute('data-delete-petani');

                if (confirm('Are you sure you want to delete this petani?')) {
                    this.submit(); // Submit the form directly
                }
            });
        });

        // Add New Petani
        const addPetaniForm = document.querySelector('#addPetaniModal form');

        if (addPetaniForm) {
            let isSubmitting = false;

            addPetaniForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // Cek jika sedang dalam proses submit
                if (isSubmitting) {
                    return;
                }

                // Ambil tombol submit
                const submitButton = this.querySelector('button[type="submit"]');

                // Set flag dan nonaktifkan tombol
                isSubmitting = true;
                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    this.submit(); // Submit form langsung
                } catch (error) {
                    // Jika terjadi error, kembalikan status
                    isSubmitting = false;
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                    console.error('Error submitting form:', error);
                }
            });
        } else {
            console.error('Add Petani form not found');
        }

        // Edit Petani
        document.querySelectorAll('form[id^="editPetaniForm"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                this.submit(); // Submit the form directly
            });
        });

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchResults = document.getElementById('search-results');

        // Auto-complete functionality
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            if (query.length > 0) {
                fetch(`/search-petani?term=${encodeURIComponent(query)}`, {

                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = ''; // Clear previous results
                        if (data.length > 0) {
                            data.forEach(petani => {
                                const div = document.createElement('div');
                                div.classList.add('dropdown-item');
                                div.textContent = petani.nama;
                                div.addEventListener('click', () => {
                                    searchInput.value = petani.nama;
                                    searchResults.style.display = 'none';
                                    document.querySelector('form').submit();
                                });
                                searchResults.appendChild(div);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        searchResults.style.display = 'none';
                    });
            } else {
                searchResults.style.display = 'none';
            }
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                searchResults.style.display = 'none';
            }
        });
    });
</script>

<!-- Pagination -->
<div class="d-flex justify-content-between align-items-center pas-2 mt-3 mx-4">
    <div>
        Showing
        <strong>{{ $petanis->firstItem() }}</strong> to
        <strong>{{ $petanis->lastItem() }}</strong> of
        <strong>{{ $petanis->total() }}</strong> entries
    </div>

    <div>
        {{ $petanis->appends(request()->input())->links('pagination::bootstrap-4') }}
    </div>
</div>



@endsection