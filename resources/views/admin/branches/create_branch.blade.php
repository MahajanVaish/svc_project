@extends('admin.layouts.layouts')
@section('title', 'Create Branch')
@section('content')
    <style>
        /* Action Buttons Styling */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-edit-square {
            border: 1px solid #16a34a;
            color: #16a34a;
        }

        .btn-edit-square:hover {
            background-color: #16a34a;
            color: white;
        }

        .btn-delete-square {
            border: 1px solid #dc3545;
            color: #dc3545;
        }

        .btn-delete-square:hover {
            background-color: #dc3545;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
    <div class="container px-0 pb-md-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Inquiry Price Section -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light px-3 py-3">
                <h5 class="mb-0 fw-bold" style="color: #197040">Inquiry Price</h5>
            </div>
            <div class="card-body bg-light">
                <form action="" method="POST">
                    @csrf
                    <div class="row g-3 mb-3 pb-2">
                        <div class="col-md-6">
                            <input type="number" name="charges_price" class="form-control" placeholder="Price">
                        </div>
                    </div>
                    <div class="border-bottom mb-3"></div>
                    <div class="text-start">
                        <button type="submit" class="btn btn-success px-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- New Branch Section -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light px-3 py-3">
                <h5 class="mb-0 fw-bold">New Branch</h5>
            </div>
            <div class="card-body bg-light">
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf
                    <div class="row g-4 mb-3 pb-2">
                        <div class="col-md-4">
                            <input type="text" name="branch_name"
                                class="form-control @error('branch_name') is-invalid @enderror" placeholder="Branch Name"
                                value="{{ old('branch_name') }}">
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <select name="show_branch"
                                class="form-select form-control @error('show_branch') is-invalid @enderror"
                                aria-label="Default select example">
                                <option value="">Select one</option>
                                <option value="FNF" {{ old('show_branch') == 'FNF' ? 'selected' : '' }}>FNF</option>
                                <option value="New" {{ old('show_branch') == 'New' ? 'selected' : '' }}>New</option>
                            </select>
                            @error('show_branch')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="branch_short_name"
                                class="form-control @error('branch_short_name') is-invalid @enderror"
                                placeholder="Branch Short Name" value="{{ old('branch_short_name') }}">
                            @error('branch_short_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                id="exampleFormControlTextarea1" rows="2"
                                placeholder="Address">{{ old('address') }}</textarea>
                            @error('address')
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

        <!-- Manage Branch Section -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light px-3 py-3">
                <h5 class="mb-0 fw-bold">Manage Branch</h5>
            </div>
            <div class="card-body border rounded bg-light mx-3 mt-3 py-2">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center col-md-3">
                        <label class="me-2">Show</label>
                        <select class="form-select form-select-sm w-auto" id="perPageSelect">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label class="ms-2">per page</label>
                    </div>
                    <div class="col-md-3 py-1">
                        <input type="text" class="form-control" placeholder="Search" id="searchInput">
                    </div>
                </div>
            </div>
            <div class="card-body bg-white">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="text-white" style="background: #035c23;">
                            <tr>
                                <th class="py-2.5 px-3" width="20">#</th>
                                <th class="py-2.5 px-3">Branch ID</th>
                                <th class="py-2.5 px-3">Branch</th>
                                <th class="py-2.5 px-3">Parent Branch</th>
                                <th class="py-2.5 px-3">Address</th>
                                <th class="py-2.5 px-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $index => $branch)
                                <tr>
                                    <td class="py-2.5 px-3">{{ $branches->firstItem() + $index }}</td>
                                    <td class="py-2.5 px-3">{{ $branch->branch_id }}</td>
                                    <td class="py-2.5 px-3">{{ $branch->branch_name }}</td>
                                    <td class="py-2.5 px-3">{{ $branch->show_branch }}</td>
                                    <td class="py-2.5 px-3">{{ Str::limit($branch->address, 80) }}</td>
                                    <td class="py-2.5 px-3">
                                        <div class="action-buttons justify-content-center">
                                            <!-- Edit Button - SIMPLE VERSION like charges -->
                                            <a href="#" class="action-btn btn-edit-square" onclick="
                                                            document.getElementById('edit_branch_name').value = '{{ $branch->branch_name }}';
                                                            document.getElementById('edit_show_branch').value = '{{ $branch->show_branch }}';
                                                            document.getElementById('edit_address').value = '{{ $branch->address }}';
                                                            document.getElementById('editBranchForm').action = '/branches/{{ $branch->id }}';
                                                            new bootstrap.Modal(document.getElementById('editBranchModal')).show();
                                                            return false;
                                                       ">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('branch.delete', $branch->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-delete-square"
                                                    onclick="return confirm('Are you sure you want to delete this branch?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">No branches found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span>Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }}
                        entries</span>

                    @if ($branches->hasPages())
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item {{ $branches->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" style="font-size: 14px;" href="{{ $branches->previousPageUrl() }}" tabindex="-1">Previous</a>
                                </li>

                                @foreach ($branches->getUrlRange(1, $branches->lastPage()) as $page => $url)
                                    @if ($page == $branches->currentPage())
                                        <li class="page-item active"><span class="page-link" style="font-size: 14px;">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item" style="font-size: 14px;"><a class="page-link"
                                                href="{{ $url }}" style="font-size: 14px;">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                <li class="page-item {{ !$branches->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" style="font-size: 14px;" href="{{ $branches->nextPageUrl() }}">Next</a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>

        <!-- New Users Section -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light px-3 py-3">
                <h5 class="mb-0 fw-bold">New Users</h5>
            </div>
            <div class="card-body bg-light">
                <form action="{{route('admin.users.store')}}" method="POST">
                    @csrf
                    <div class="text-danger fw-bold mb-2" style="font-size: 14px">(Please make sure the Username is
                        correct as it cannot be changed after setup)</div>
                    <div class="row g-4 mb-3 pb-2">
                        <div class="col-md-4">
                            <input type="email" name="email" class="form-control" placeholder="User Email">
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <input type="text" name="name" class="form-control" placeholder="User Name">
                                <div style="font-size: 13px; color:#808080;">(Use only underscore.)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="password" name="password" class="form-control" placeholder="User Password">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select form-control" name="user_role" required>
                                <option value="" selected>Select Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6">
                            <select class="form-select form-control" name="user_branch" aria-label="Default select example">
                                <option value="" selected>Select Branches</option>

                                @foreach ($branches as $branch)
                                    <option value="{{$branch->branch_id}}">{{ $branch->branch_name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="border-bottom mb-3"></div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Branch Modal - SIMPLE VERSION like charges -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBranchForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_branch_name" class="form-label">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" id="edit_branch_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_show_branch" class="form-label">Parent Branch</label>
                                <select name="show_branch" class="form-select" id="edit_show_branch" required>
                                    <option value="FNF">FNF</option>
                                    <option value="New">New</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="edit_address" class="form-label">Address</label>
                                <textarea name="address" class="form-control" id="edit_address" rows="3"
                                    required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update Branch</button>
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
            // Per page select change
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function () {
                    const perPage = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                });
            }

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function (e) {
                    if (e.key === 'Enter') {
                        const searchTerm = this.value;
                        const url = new URL(window.location.href);
                        if (searchTerm) {
                            url.searchParams.set('search', searchTerm);
                        } else {
                            url.searchParams.delete('search');
                        }
                        window.location.href = url.toString();
                    }
                });
            }
        });
    </script>
@endpush