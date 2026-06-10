@extends('admin.layouts.layouts')

@section('title', 'Profile Settings')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white dark:bg-slate-800 border-bottom border-slate-100 dark:border-slate-700 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-user-circle fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 text-slate-800 dark:text-slate-100 fw-bold">Profile Settings</h4>
                            <p class="mb-0 text-secondary dark:text-slate-400 text-sm">Manage your account information and password.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white dark:bg-slate-800 p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 bg-teal-50 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300 d-flex align-items-center mb-4 rounded-3 shadow-sm">
                            <i class="fas fa-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Profile Image Section -->
                        <div class="text-center mb-5">
                            <div class="position-relative d-inline-block">
                                <div class="rounded-circle overflow-hidden border border-4 border-white dark:border-slate-700 shadow-sm" style="width: 120px; height: 120px;">
                                    @if($user->profile_image)
                                        <img src="{{ asset($user->profile_image) }}" id="preview-image" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div id="preview-placeholder" class="w-100 h-100 bg-slate-100 dark:bg-slate-900 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user fs-1 text-slate-300 dark:text-slate-600"></i>
                                        </div>
                                        <img src="" id="preview-image" class="w-100 h-100 object-fit-cover d-none">
                                    @endif
                                </div>
                                
                                <label for="profile_image" class="position-absolute bottom-0 end-0 bg-teal-600 text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm cursor-pointer hover:bg-teal-700 transition" style="width: 36px; height: 36px; border: 2px solid white;">
                                    <i class="fas fa-camera text-sm"></i>
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*" onchange="previewProfileImage(this)">
                            </div>
                            
                            <h5 class="mt-3 mb-1 text-slate-800 dark:text-slate-100 fw-bold">{{ $user->name }}</h5>
                            <p class="text-secondary dark:text-slate-400 mb-0 small">{{ $user->email }}</p>
                            
                            @error('profile_image')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Basic Information -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-xs uppercase tracking-wider text-secondary dark:text-slate-400 fw-bold mb-2">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-secondary dark:text-slate-400">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500" 
                                        placeholder="Enter your full name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-xs uppercase tracking-wider text-secondary dark:text-slate-400 fw-bold mb-2">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-secondary dark:text-slate-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500" 
                                        placeholder="Enter your email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="border-slate-100 dark:border-slate-700 my-4">

                        <!-- Password Section -->
                        <div class="mb-4">
                            <h6 class="text-slate-800 dark:text-slate-200 fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-lock text-teal-600 dark:text-teal-400"></i> Change Password
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-xs uppercase tracking-wider text-secondary dark:text-slate-400 fw-bold mb-2">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-secondary dark:text-slate-400">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <input type="password" name="password" class="form-control border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500" 
                                            placeholder="Enter new password">
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-xs uppercase tracking-wider text-secondary dark:text-slate-400 fw-bold mb-2">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-secondary dark:text-slate-400">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" class="form-control border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 focus:border-teal-500 focus:ring-teal-500" 
                                            placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end pt-2">
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 shadow-sm">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewProfileImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewImage = document.getElementById('preview-image');
            var placeholder = document.getElementById('preview-placeholder');
            
            previewImage.src = e.target.result;
            previewImage.classList.remove('d-none');
            
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
