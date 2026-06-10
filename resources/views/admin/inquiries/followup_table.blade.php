@forelse($followupPatients as $index => $patient)
    @php
        $nextFollowupDate = $patient->next_followup_date;
        $today = \Carbon\Carbon::today();
        $followupCarbon = $nextFollowupDate ? \Carbon\Carbon::parse($nextFollowupDate) : null;
        $daysCount = $followupCarbon ? $today->diffInDays($followupCarbon, false) : null;
        $isToday = $daysCount === 0;
        $isTomorrow = $daysCount === 1;
        $isPast = $daysCount !== null && $daysCount < 0;

        // Fetch patient profile image path
        $opt = \App\Models\Opt::where('patient_id', $patient->patient_id)
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
            $optIds = \App\Models\Opt::where('patient_id', $patient->patient_id)
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
    @endphp
    <tr>
        <td class="text-center py-2.5 px-3">
            <strong>{{ ($followupPatients->currentPage() - 1) * $followupPatients->perPage() + $index + 1 }}</strong>
        </td>
        <td class="text-center py-2.5 px-3">
            <div class="d-flex justify-content-center">
                <div class="position-relative">
                    <div class="rounded-circle d-flex align-items-center justify-content-center profile-avatar"
                        style="width: 38px; height: 38px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3); overflow: hidden; position: relative;"
                        onclick="window.location.href='{{ route('patient.profile', $patient->id) }}'">
                        <span class="avatar-initial" style="color: white; font-weight: bold; font-size: 13px;">
                            {{ strtoupper(substr($patient->patient_f_name, 0, 1)) }}{{ strtoupper(substr($patient->patient_l_name, 0, 1)) }}
                        </span>
                        @if($profileImagePath)
                            <img src="{{ $profileImagePath }}" alt="Profile Image"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                                onload="const initial = this.closest('.profile-avatar').querySelector('.avatar-initial'); if(initial) initial.style.display='none';"
                                onerror="this.remove();">
                        @endif
                    </div>
                    @if($isToday)
                        <div class="position-absolute" style="top: -4px; right: -4px;">
                            <span class="badge-pulse"
                                style="background-color: #dc3545; color: white; font-size: 8px; padding: 1px 4px; border-radius: 3px; font-weight: 600;">Today</span>
                        </div>
                    @endif
                </div>
            </div>
        </td>
        <td class="py-2.5 px-3">
            <a href="{{ route('patient.profile', $patient->id) }}"
                style="text-decoration: none; color: var(--accent-solid); font-weight: 600;">
                {{ $patient->patient_id ?? 'N/A' }}
            </a>
        </td>
        <td class="py-2.5 px-3">
            <div style="font-weight: 600; color: var(--text-primary);">
                {{ $patient->patient_f_name }} {{ $patient->patient_m_name }} {{ $patient->patient_l_name }}
            </div>
            @if($patient->age)
                <div style="font-size: 11px; color: var(--text-muted);">
                    {{ $patient->age }} years @if($patient->gender) • {{ $patient->gender }} @endif
                </div>
            @endif
        </td>
        <td class="py-2.5 px-3">
            @if($nextFollowupDate)
                <div class="d-flex align-items-center">
                    <div class="me-2 p-1.5 rounded"
                        style="background: {{ $isToday ? 'rgba(220, 53, 69, 0.1)' : ($isTomorrow ? 'rgba(255, 193, 7, 0.15)' : 'rgba(13, 110, 253, 0.08)') }};">
                        <i class="fas fa-calendar-alt"
                            style="font-size: 14px; color: {{ $isToday ? '#dc3545' : ($isTomorrow ? '#f59e0b' : '#0d6efd') }};"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 13px;">
                            {{ \Carbon\Carbon::parse($nextFollowupDate)->format('d M Y') }}
                        </div>
                        @if($isToday)
                            <span class="status-badge badge-pending" style="font-size: 9px; padding: 2px 6px;">Today</span>
                        @elseif($isTomorrow)
                            <span class="status-badge badge-diet"
                                style="font-size: 9px; padding: 2px 6px; background-color: rgba(255, 193, 7, 0.1); color: #f59e0b; border-color: rgba(255, 193, 7, 0.2);">In
                                1 day</span>
                        @elseif($daysCount > 1)
                            <span class="status-badge badge-joined" style="font-size: 9px; padding: 2px 6px;">In {{ $daysCount }}
                                days</span>
                        @elseif($isPast)
                            <span class="status-badge badge-pending"
                                style="font-size: 9px; padding: 2px 6px; background-color: rgba(108, 117, 125, 0.1); color: #6c757d; border-color: rgba(108, 117, 125, 0.2);">{{ abs($daysCount) }}
                                days ago</span>
                        @endif
                    </div>
                </div>
            @else
                <span class="text-muted">No date</span>
            @endif
        </td>
        <td class="py-2.5 px-3">
            @if($patient->phone_no)
                <div class="d-flex align-items-center">
                    <i class="fas fa-phone me-2" style="color: #28a745; font-size: 12px;"></i>
                    <a href="tel:{{ $patient->phone_no }}" style="text-decoration: none; color: var(--text-primary);">
                        {{ $patient->phone_no }}
                    </a>
                </div>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="py-2.5 px-3">
            @if($patient->diagnosis)
                <span class="status-badge badge-diagnosis" title="{{ $patient->diagnosis }}">
                    {{ Str::limit($patient->diagnosis, 20) }}
                </span>
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center py-2.5 px-3">
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('call.log.create', $patient->id) }}" class="action-btn"
                    style="border-color: #0d6efd; color: #0d6efd;" title="Record Call">
                    <i class="fa-solid fa-phone"></i>
                </a>
                <button onclick="editFollowupDate({{ $patient->id }}, '{{ $nextFollowupDate ?? '' }}')"
                    class="action-btn btn-edit-square" title="Edit Date">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button onclick="deleteFollowupDate({{ $patient->id }})" class="action-btn btn-delete-square"
                    title="Remove Date">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">
            <div class="py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No follow-up patients found</h5>
                <p class="text-muted mb-0">There are no patients with upcoming follow-up dates.</p>
                <a href="{{ route('add.inquiry') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Add New Inquiry
                </a>
            </div>
        </td>
    </tr>
@endforelse

@if($followupPatients->hasPages())
    <tr>
        <td colspan="8">
            <div class="d-flex justify-content-between align-items-center p-3 mt-3 border-top">
                <div style="font-size: 13px; color: var(--text-secondary);">
                    Showing {{ $followupPatients->firstItem() }} to {{ $followupPatients->lastItem() }} of
                    {{ $followupPatients->total() }} entries
                </div>
                <div>
                    {{ $followupPatients->appends(request()->query())->links() }}
                </div>
            </div>
        </td>
    </tr>
@endif