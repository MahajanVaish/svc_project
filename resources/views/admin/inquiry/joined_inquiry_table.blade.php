@forelse($inquiries as $index => $inquiry)
    <tr>
        <td class="py-2.5 px-3">
            <a href="{{ route('patient.profile', $inquiry->id) }}" class="text-decoration-none">
                <div class="profile-circle" title="View Patient Profile">
                    @php
                        $opt = \App\Models\Opt::where('patient_id', $inquiry->patient_id)
                            ->where(function ($q) {
                                $q->whereNull('delete_status')
                                    ->orWhere('delete_status', '')
                                    ->orWhere('delete_status', '0');
                            })
                            ->orderByDesc('created_at')
                            ->first();

                        $profileImagePath = null;
                        if ($opt) {
                            $profileImage = \App\Models\OptMeta::where('opt_id', $opt->id)
                                ->where('meta_key', 'profile_image')
                                ->value('meta_value');
                            
                            $beforeProfilePhoto = \App\Models\OptMeta::where('opt_id', $opt->id)
                                ->where('meta_key', 'before_profile_photo')
                                ->value('meta_value');

                            if (empty($beforeProfilePhoto)) {
                                $beforeProfilePhoto = \App\Models\OptMeta::where('opt_id', $opt->id)
                                    ->where('meta_key', 'before_picture_1')
                                    ->value('meta_value');
                            }

                            $afterProfilePhoto = \App\Models\OptMeta::where('opt_id', $opt->id)
                                ->where('meta_key', 'after_profile_photo')
                                ->value('meta_value');

                            if (empty($afterProfilePhoto)) {
                                $afterProfilePhoto = \App\Models\OptMeta::where('opt_id', $opt->id)
                                    ->where('meta_key', 'after_picture_1')
                                    ->value('meta_value');
                            }

                            if ($profileImage) {
                                $profileImagePath = asset($profileImage);
                            } elseif ($beforeProfilePhoto && file_exists(public_path('before/' . $beforeProfilePhoto))) {
                                $profileImagePath = asset('before/' . $beforeProfilePhoto);
                            } elseif ($afterProfilePhoto && file_exists(public_path('after/' . $afterProfilePhoto))) {
                                $profileImagePath = asset('after/' . $afterProfilePhoto);
                            }
                        }

                        if (empty($profileImagePath)) {
                            $optIds = \App\Models\Opt::where('patient_id', $inquiry->patient_id)
                                ->where(function ($q) {
                                    $q->whereNull('delete_status')
                                        ->orWhere('delete_status', '')
                                        ->orWhere('delete_status', '0');
                                })
                                ->pluck('id');

                            if ($optIds->isNotEmpty()) {
                                $profileImage = \App\Models\OptMeta::whereIn('opt_id', $optIds)
                                    ->where('meta_key', 'profile_image')
                                    ->orderByDesc('id')
                                    ->value('meta_value');
                                if ($profileImage) {
                                    $profileImagePath = asset($profileImage);
                                }
                            }
                        }

                        $fullName = '';
                        if (!empty($inquiry->patient_name)) {
                            $fullName = $inquiry->patient_name;
                        } else {
                            $nameParts = array_filter([
                                $inquiry->patient_f_name ?? '',
                                $inquiry->patient_m_name ?? '',
                                $inquiry->patient_l_name ?? ''
                            ]);
                            $fullName = implode(' ', $nameParts);
                        }
                        $initial = !empty($fullName) ? strtoupper(substr($fullName, 0, 1)) : 'N';
                    @endphp

                    <span class="profile-initial">{{ $initial }}</span>
                    @if($profileImagePath)
                        <img src="{{ $profileImagePath }}" alt="Profile Image"
                            onload="this.closest('.profile-circle').querySelector('.profile-initial').style.display='none';"
                            onerror="this.remove();">
                    @endif
                </div>
            </a>
        </td>
        <td class="py-2.5 px-3">{{ $inquiry->patient_id ?? 'N/A' }}</td>
        <td class="py-2.5 px-3">
            @php
                $rawDate = $inquiry->getRawOriginal('inquiry_date') ?? null;
                if ($rawDate) {
                    try {
                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $rawDate)) {
                            echo \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('d/m/Y');
                        } else {
                            echo \Carbon\Carbon::parse($rawDate)->format('d/m/Y');
                        }
                    } catch (\Exception $e) {
                        echo $rawDate;
                    }
                } elseif (!empty($inquiry->created_at)) {
                    echo \Carbon\Carbon::parse($inquiry->created_at)->format('d/m/Y');
                } else {
                    echo 'N/A';
                }
            @endphp
        </td>
        <td class="py-2.5 px-3">{{ $inquiry->patient_name ?? 'N/A' }}</td>
        <td class="py-2.5 px-3">{{ $inquiry->phone_no ?? 'N/A' }}</td>
        <td class="py-2.5 px-3">
            @php
                $programs = [];
                $optIds = \App\Models\Opt::where('patient_id', $inquiry->patient_id)
                    ->where(function ($q) {
                        $q->whereNull('delete_status')
                            ->orWhere('delete_status', '')
                            ->orWhere('delete_status', '0');
                    })
                    ->pluck('id');

                if ($optIds->isNotEmpty()) {
                    // 1. Fetch from programs_array (JSON array from Diet H/O)
                    $pArray = \App\Models\OptMeta::whereIn('opt_id', $optIds)
                        ->where('meta_key', 'programs_array')
                        ->orderByDesc('id')
                        ->value('meta_value');

                    if ($pArray) {
                        $decodedProgs = json_decode($pArray, true) ?: [];
                        $programs = array_merge($programs, collect($decodedProgs)->pluck('program')->filter()->toArray());
                    }

                    // 2. Fetch from selected_program (string from Diet H/O)
                    $single = \App\Models\OptMeta::whereIn('opt_id', $optIds)
                        ->where('meta_key', 'selected_program')
                        ->orderByDesc('id')
                        ->value('meta_value');
                    if ($single) {
                        $programs[] = $single;
                    }

                    // 3. Fetch from program_name (from inquiry registration)
                    $progName = \App\Models\OptMeta::whereIn('opt_id', $optIds)
                        ->where('meta_key', 'program_name')
                        ->orderByDesc('id')
                        ->value('meta_value');
                    if ($progName) {
                        $progNameClean = preg_replace('/(\d),(\d)/', '$1$2', $progName);
                        $splitNames = array_map('trim', explode(',', $progNameClean));
                        $programs = array_merge($programs, $splitNames);
                    }

                    // 4. Fetch from online_program_label (from inquiry registration)
                    $onlineLabel = \App\Models\OptMeta::whereIn('opt_id', $optIds)
                        ->where('meta_key', 'online_program_label')
                        ->orderByDesc('id')
                        ->value('meta_value');
                    if ($onlineLabel) {
                        $programs[] = $onlineLabel; 
                    }
                }

                // Clean and deduplicate programs
                $programs = array_unique(array_filter($programs));

                $colorClasses = ['badge-pg-1', 'badge-pg-2', 'badge-pg-3', 'badge-pg-4', 'badge-pg-5', 'badge-pg-6', 'badge-pg-7', 'badge-pg-8'];
            @endphp

            @forelse($programs as $prog)
                <span class="status-badge {{ $colorClasses[array_rand($colorClasses)] }}" title="{{ $prog }}">
                    {{ Str::limit($prog, 20) }}
                </span>
            @empty
                <span class="text-muted">-</span>
            @endforelse
        </td>
        <td class="py-2.5 px-3">
            @if($inquiry->diagnosis)
                <span class="status-badge badge-diagnosis" title="{{ $inquiry->diagnosis }}">
                    {{ Str::limit($inquiry->diagnosis, 20) }}
                </span>
            @else
                <span class="text-muted">N/A</span>
            @endif
        </td>
        <td class="py-2.5 px-3">
            <a href="{{ route('diet.join.patient', $inquiry->id) }}" style="color: #28a745; text-decoration: none;"
                title="View/Edit Diet Chart">
                Diet H/O
            </a>
        </td>
        <td class="text-center py-2.5 px-3">
            <a href="{{ route('call.log.create', $inquiry->id) }}" class="action-btn"
                style="border-color: #0d6efd; color: #0d6efd;" title="Record Call">
                <i class="fa-solid fa-phone"></i>
            </a>
        </td>
        <td class="text-center py-2.5 px-3">
            <button onclick="editInquiry({{ $inquiry->id }})" class="action-btn btn-edit-square" title="Edit">
                <i class="fa-regular fa-pen-to-square"></i>
            </button>
        </td>
        <td class="text-center py-2.5 px-3">
            <form action="{{ route('delete.inquiry', $inquiry->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="action-btn btn-delete-square"
                    onclick="confirmDelete(this.closest('form'), '{{ addslashes($inquiry->patient_name ?? ($inquiry->patient_f_name ?? "this patient")) }}')">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">
            <div class="py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No joined patients found</h5>
                <p class="text-muted mb-0">No patients have joined yet or add a new patient</p>
                <a href="{{ route('add.inquiry') }}?default_status=Joined" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Add New Patient
                </a>
            </div>
        </td>
    </tr>
@endforelse

@if($inquiries->hasPages())
    <tr>
        <td colspan="11">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    @if($inquiries->total() > 0)
                        Showing {{ $inquiries->firstItem() }} to {{ $inquiries->lastItem() }} of {{ $inquiries->total() }}
                        joined patients
                    @else
                        Showing 0 to 0 of 0 entries
                    @endif
                </div>

                <div>
                    {{ $inquiries->links() }}
                </div>
            </div>
        </td>
    </tr>
@endif