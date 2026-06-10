@extends('admin.layouts.layouts')
@section('title', 'Manage Programs')
@section('content')

<style>
    .badge {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-transform: capitalize;
    }

    /* Theme-Aware Gender Badges */
    .badge-both {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .badge-male {
        background-color: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
        border: 1px solid rgba(14, 165, 233, 0.2);
    }

    .badge-female {
        background-color: rgba(236, 72, 153, 0.1);
        color: #ec4899;
        border: 1px solid rgba(236, 72, 153, 0.2);
    }

    .dark .badge-both { background-color: rgba(59, 130, 246, 0.2); color: #60a5fa; }
    .dark .badge-male { background-color: rgba(14, 165, 233, 0.2); color: #38bdf8; }
    .dark .badge-female { background-color: rgba(236, 72, 153, 0.2); color: #f472b6; }

    /* Branch Badges */
    .badge-all {
        background-color: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .badge-lhb {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .dark .badge-all { background-color: rgba(156, 163, 175, 0.2); color: #9ca3af; }
    .dark .badge-lhb { background-color: rgba(16, 185, 129, 0.2); color: #34d399; }

    /* Action Icons Standardized */
    .btn-edit-square, .btn-delete-square {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
        background: transparent;
    }

    .btn-edit-square {
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.4);
    }

    .btn-edit-square:hover {
        background-color: #10b981;
        color: white !important;
        border-color: #10b981;
    }

    .btn-delete-square {
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.4);
    }

    .btn-delete-square:hover {
        background-color: #ef4444 !important;
        color: white !important;
        border-color: #ef4444;
    }

    /* Component Overrides for Theme Consistency */
    .card {
        background-color: var(--card-bg) !important;
        border-color: var(--border-subtle) !important;
    }

    .card-header {
        background-color: var(--bg-hover) !important;
        border-bottom-color: var(--border-subtle) !important;
        color: var(--text-primary) !important;
    }

    .table {
        color: var(--text-primary) !important;
    }

    .table thead th {
        background-color: var(--accent-solid) !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--bg-hover) !important;
    }

    .form-control, .form-select {
        background-color: var(--bg-main) !important;
        border-color: var(--border-subtle) !important;
        color: var(--text-primary) !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-solid) !important;
        box-shadow: 0 0 0 0.25rem var(--accent-glow) !important;
    }

    .text-success { color: #10b981 !important; }
    .btn-success {
        background-color: var(--accent-solid) !important;
        border-color: var(--accent-solid) !important;
    }

    .btn-success:hover {
        background-color: var(--accent-hover) !important;
        border-color: var(--accent-hover) !important;
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

    <!-- New Programs Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header px-3 py-3">
            <h5 class="mb-0 fw-bold">New Programs</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.manage-programs.store') }}" method="POST">
                @csrf
                <div class="row g-4 mb-3 pb-2">
                    <div class="col-md-4">
                        <label class="form-label">Program Name</label>
                        <input type="text" name="program_name"
                               class="form-control @error('program_name') is-invalid @enderror"
                               placeholder="Program Name"
                               value="{{ old('program_name') }}" required>
                        @error('program_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Program Short Name</label>
                        <input type="text" name="program_short_name"
                               class="form-control @error('program_short_name') is-invalid @enderror"
                               placeholder="Program Short Name"
                               value="{{ old('program_short_name') }}" required>
                        @error('program_short_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Select Branch</label>
                        <select name="branch" class="form-select @error('branch') is-invalid @enderror" required>
                            <option value="">Select Branch</option>
                            <option value="ALL" {{ old('branch') == 'ALL' ? 'selected' : '' }}>ALL</option>
                            <option value="LHR" {{ old('branch') == 'LHR' ? 'selected' : '' }}>LHR</option>
                            <option value="Other" {{ old('branch') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('branch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="Both" {{ old('gender') == 'Both' ? 'selected' : '' }}>Both</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Price</label>
                        <input type="number" name="program_price" step="0.01"
                               class="form-control @error('program_price') is-invalid @enderror"
                               placeholder="Price"
                               value="{{ old('program_price') }}" required>
                        @error('program_price')
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

    <!-- Manage Programs Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header px-3 py-3">
            <h5 class="mb-0 fw-bold">Manage Programs</h5>
        </div>

        <!-- Search and Filter Section -->
        <div class="card-body border rounded mx-3 mt-3 py-2">
            <form action="{{ route('admin.manage-programs') }}" method="GET" id="searchForm">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center col-md-3">
                        <label class="me-2">Show</label>
                        <select class="form-select form-select-sm w-auto" name="per_page" id="perPageSelect">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label class="ms-2">per page</label>
                    </div>
                    <div class="col-md-5 py-1">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search programs..." name="search"
                                   value="{{ request('search') }}" id="searchInput">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.manage-programs') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body bg-white">
            @if(request('search'))
                <div class="alert alert-info mb-3">
                    Search results for: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('admin.manage-programs') }}" class="float-end">Clear search</a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="text-white" style="background: #035c23;">
                        <tr>
                            <th class="py-2.5 px-3" width="20">#</th>
                            <th class="py-2.5 px-3">Program Name</th>
                            <th class="py-2.5 px-3">Program Short Name</th>
                            <th class="py-2.5 px-3">Gender</th>
                            <th class="py-2.5 px-3">Branch</th>
                            <th class="py-2.5 px-3">Price</th>
                            <th class="py-2.5 px-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $index => $program)
                            <tr>
                                <td class="py-2.5 px-3">{{ $programs->firstItem() + $index }}</td>
                                <td class="py-2.5 px-3">{{ $program->program_name }}</td>
                                <td class="py-2.5 px-3">{{ $program->program_short_name }}</td>
                                <td class="py-2.5 px-3">
                                    @if($program->gender == 'Both')
                                        <span class="badge badge-both">{{ $program->gender }}</span>
                                    @elseif($program->gender == 'Male')
                                        <span class="badge badge-male">{{ $program->gender }}</span>
                                    @elseif($program->gender == 'Female')
                                        <span class="badge badge-female">{{ $program->gender }}</span>
                                    @else
                                        <span class="badge">{{ $program->gender }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">
                                    @if($program->branch == 'ALL')
                                        <span class="badge badge-all">{{ $program->branch }}</span>
                                    @elseif($program->branch == 'LHB')
                                        <span class="badge badge-lhb">{{ $program->branch }}</span>
                                    @else
                                        <span class="badge">{{ $program->branch }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3">₹ {{ number_format($program->program_price, 0) }}</td>
                                <td class="py-2.5 px-3">
                                    <div class="d-flex gap-2">
                                        <!-- Edit Button -->
                                        <a href="#" class="btn-edit-square"
                                            title="Edit"
                                            onclick="
                                                document.getElementById('edit_program_name').value = '{{ $program->program_name }}';
                                                document.getElementById('edit_program_short_name').value = '{{ $program->program_short_name }}';
                                                document.getElementById('edit_price').value = '{{ $program->program_price }}';
                                                document.getElementById('editProgramForm').action = '/manage-programs/update/{{ $program->id }}';
                                                new bootstrap.Modal(document.getElementById('editProgramModal')).show();
                                                return false;
                                           ">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.manage-programs.delete', $program->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-square"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this program?')">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    @if(request('search'))
                                        No programs found matching "{{ request('search') }}"
                                    @else
                                        No programs found. Add your first program using the form above.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($programs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>Showing {{ $programs->firstItem() }} to {{ $programs->lastItem() }} of {{ $programs->total() }}
                    entries</span>

                <nav>
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($programs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="font-size: 14px;">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                @php
                                    $prevUrl = $programs->previousPageUrl();
                                    if(request('search')) {
                                        $prevUrl .= '&search=' . request('search');
                                    }
                                    if(request('per_page')) {
                                        $prevUrl .= '&per_page=' . request('per_page');
                                    }
                                @endphp
                                <a class="page-link" style="font-size: 14px;" href="{{ $prevUrl }}" tabindex="-1">Previous</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $current = $programs->currentPage();
                            $last = $programs->lastPage();
                            $dotted = false;
                        @endphp

                        @for ($i = 1; $i <= $last; $i++)
                            @if ($i == 1 || $i == $last || ($i >= $current - 1 && $i <= $current + 1))
                                @php
                                    $pageUrl = $programs->url($i);
                                    if(request('search')) {
                                        $pageUrl .= '&search=' . request('search');
                                    }
                                    if(request('per_page')) {
                                        $pageUrl .= '&per_page=' . request('per_page');
                                    }
                                @endphp

                                @if ($i == $current)
                                    <li class="page-item active"><span class="page-link" style="font-size: 14px;">{{ $i }}</span></li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" style="font-size: 14px;" href="{{ $pageUrl }}">{{ $i }}</a>
                                    </li>
                                @endif
                                @php $dotted = false; @endphp
                            @elseif (!$dotted)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                @php $dotted = true; @endphp
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($programs->hasMorePages())
                            <li class="page-item">
                                @php
                                    $nextUrl = $programs->nextPageUrl();
                                    if(request('search')) {
                                        $nextUrl .= '&search=' . request('search');
                                    }
                                    if(request('per_page')) {
                                        $nextUrl .= '&per_page=' . request('per_page');
                                    }
                                @endphp
                                <a class="page-link" style="font-size: 14px;" href="{{ $nextUrl }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" style="font-size: 14px;">Next</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @else
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span>Showing {{ $programs->firstItem() }} to {{ $programs->lastItem() }} of {{ $programs->total() }}
                    entries</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Program Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1" aria-labelledby="editProgramModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProgramModalLabel">Edit Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProgramForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_program_name" class="form-label">Program Name</label>
                            <input type="text" name="program_name" class="form-control" id="edit_program_name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_program_short_name" class="form-label">Program Short Name</label>
                            <input type="text" name="program_short_name" class="form-control" id="edit_program_short_name"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" name="program_price" step="0.01"
                                   class="form-control" id="edit_price" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when per_page changes
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    document.getElementById('searchForm').submit();
                });
            }

            // Auto-submit form on Enter key in search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        document.getElementById('searchForm').submit();
                    }
                });
            }
        });
    </script>
@endpush
