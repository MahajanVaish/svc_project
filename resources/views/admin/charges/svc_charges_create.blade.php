@extends('admin.layouts.layouts')
@section('title', 'SVC Charges')
@section('content')

<div class="container px-0">
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-light px-3 py-3">
            <h5 class="mb-0 fw-bold">SVC Charges</h5>
        </div>
        <div class="card-body bg-light">


            <form action="{{ route('svc.charges.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3 pb-2">
                    <div class="col-md-6">
                        <input type="text" name="charges_name"
                            class="form-control @error('charges_name') is-invalid @enderror" placeholder="Charges Name"
                            value="{{ old('charges_name') }}" required>
                        @error('charges_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="number" name="charges_price"
                            class="form-control @error('charges_price') is-invalid @enderror" placeholder="Price"
                            value="{{ old('charges_price') }}" step="0.01" min="0" required>
                        @error('charges_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="border-bottom mb-3"></div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light px-3 py-3">
            <h5 class="mb-0 fw-bold">Manage SVC Charges</h5>
        </div>
        <div class="card-body border rounded bg-light mx-3 mt-3 py-2">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center col-md-3">
                    <label class="me-2">Show</label>
                    <select class="form-select form-select-sm w-auto" id="perPage">
                        <option value="10" {{ request('per_page', 10)==10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
                    </select>
                    <label class="ms-2">per page</label>
                </div>
                <div class="col-md-3 py-1">
                    <input type="text" class="form-control" placeholder="Search" id="searchInput"
                        value="{{ request('search') }}">
                </div>
            </div>
        </div>
        <div class="card-body bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="text-white" style="background: #035c23;">
                        <tr>
                            <th class="py-2.5 px-3">ID</th>
                            <th class="py-2.5 px-3">Charges Name</th>
                            <th class="py-2.5 px-3">Charges Price</th>
                            <th class="py-2.5 px-3" width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($charges as $charge)
                        <tr>
                            <td class="py-2.5 px-3">{{ $charge->id }}</td>
                            <td class="py-2.5 px-3">{{ $charge->charges_name }}</td>
                            <td class="py-2.5 px-3">{{ number_format($charge->charges_price, 2) }}</td>
                            <td class="py-2.5 px-3">
                                <!-- Edit Button - SIMPLE VERSION -->
                                <a href="#" class="btn btn-sm btn-outline-success me-1" onclick="
                                                document.getElementById('edit_charges_name').value = '{{ $charge->charges_name }}';
                                                document.getElementById('edit_charges_price').value = '{{ $charge->charges_price }}';
                                                document.getElementById('editChargeForm').action = '/svc/charges/{{ $charge->id }}';
                                                new bootstrap.Modal(document.getElementById('editChargeModal')).show();
                                                return false;
                                           ">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <!-- Delete Button -->
                                <button type="button" class="btn btn-sm btn-outline-danger delete-charge-btn"
                                    data-id="{{ $charge->id }}" <!-- data-name="{{ $charge->charges_name }}"> -->
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $charge->id }}"
                                    action="{{ route('svc.charges.destroy', $charge->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">No charges found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>Showing {{ $charges->firstItem() ?? 0 }} to {{ $charges->lastItem() ?? 0 }} of {{
                    $charges->total() }} entries</span>
                {{ $charges->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editChargeModal" tabindex="-1" aria-labelledby="editChargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChargeModalLabel">Edit Charge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editChargeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_charges_name" class="form-label">Charges Name</label>
                        <input type="text" class="form-control" id="edit_charges_name" name="charges_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_charges_price" class="form-label">Charges Price</label>
                        <input type="number" class="form-control" id="edit_charges_price" name="charges_price"
                            step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Simple JavaScript for other functionality
    document.addEventListener('DOMContentLoaded', function () {
        // Per page change
        const perPageSelect = document.getElementById('perPage');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                window.location.href = url.toString();
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', this.value);
                    window.location.href = url.toString();
                }
            });
        }

        // SweetAlert Delete Confirmation
        document.querySelectorAll('.delete-charge-btn').forEach(button => {
            button.addEventListener('click', function () {
                const chargeId = this.dataset.id;
                const chargeName = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to delete the charge "${chargeName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${chargeId}`).submit();
                    }
                });
            });
        });
    });
</script>
@endpush