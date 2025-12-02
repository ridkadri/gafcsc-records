{{-- TEST: File updated at 1:00 PM --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Staff Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('staff.update', $staff) }}" id="staffForm">
                        @csrf
                        @method('PUT')

                        <!-- Staff Type Selection (Read-only in Edit Mode) -->
                        <div class="mb-8 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Staff Type <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Hidden field to preserve type -->
                            <input type="hidden" name="type" value="{{ $staff->type }}">
                            
                            <div class="p-4 bg-gray-50 border-2 border-gray-300 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">
                                        @if($staff->type === 'military')
                                            🎖️
                                        @else
                                            👔
                                        @endif
                                    </span>
                                    <div>
                                        <span class="block font-medium text-gray-900">
                                            {{ $staff->type === 'military' ? 'Military Personnel' : 'Civilian Personnel' }}
                                        </span>
                                        <span class="block text-sm text-gray-500">
                                            {{ $staff->type === 'military' ? 'Armed Forces Staff' : 'Civil Service Staff' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Staff type cannot be changed after creation
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Service Number -->
                            <div class="md:col-span-2">
                                <label for="service_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Service Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                        id="service_number" 
                                        name="service_number" 
                                        value="{{ old('service_number', $staff->service_number ?? '') }}"
                                        required
                                        placeholder="Military: GH0001 | Civilian: P/SS/C123456789"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">
                                        <span class="font-medium">Format:</span> 
                                        Military: GH0001 | Civilian: 011123/C123456789
                                    </p>
                                @error('service_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $staff->name ?? '') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Department - REQUIRED FIELD -->
                            <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select id="department" 
                                        name="department" 
                                        required 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('department') border-red-500 @enderror">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ old('department', $staff->department ?? '') == $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-blue-700 flex items-start">
                                    <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>This staff will be automatically assigned to their Head of Department (if one exists for this department)</span>
                                </p>
                            </div>

                            <!-- Is Head of Department Checkbox -->
                            <div class="md:col-span-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               id="is_hod" 
                                               name="is_hod" 
                                               value="1"
                                               {{ old('is_hod', $staff->is_hod) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_hod" class="font-medium text-gray-700">
                                            This person is the Head of Department (HOD)
                                        </label>
                                        <p class="text-sm text-gray-500">
                                            Check this box to make this staff member the Head of Department for <strong>{{ $staff->department ?? 'their selected department' }}</strong>. All staff in the same department will report to them.
                                        </p>
                                        @if($staff->is_hod && $staff->subordinates()->count() > 0)
                                            <p class="text-sm text-blue-600 mt-1">
                                                ✓ Currently managing {{ $staff->subordinates()->count() }} staff members
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date of Birth
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Promotion Date -->
                            <div>
                                <label for="last_promotion_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date of Last Promotion
                                </label>
                                <input type="date" 
                                       id="last_promotion_date" 
                                       name="last_promotion_date" 
                                       value="{{ old('last_promotion_date', $staff->last_promotion_date ? $staff->last_promotion_date->format('Y-m-d') : '') }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('last_promotion_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Military-Specific Fields -->
                        <div id="militaryFields" style="display: none;" class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Military Personnel Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Rank -->
                                <div>
                                    <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">
                                        Rank <span class="text-red-500">*</span>
                                    </label>
                                    <select name="rank" 
                                            id="rank" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Rank</option>
                                        @foreach(\App\Models\Staff::getRanks() as $key => $rankName)
                                            <option value="{{ $key }}" {{ old('rank', $staff->rank ?? '') === $key ? 'selected' : '' }}>{{ $rankName }}</option>
                                        @endforeach
                                    </select>
                                    @error('rank')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sex -->
                                <div>
                                    <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sex <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sex" 
                                            id="sex" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Sex</option>
                                        <option value="Male" {{ old('sex', $staff->sex ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex', $staff->sex ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sex')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Trade -->
                                <div>
                                    <label for="trade" class="block text-sm font-medium text-gray-700 mb-2">
                                        Trade
                                    </label>
                                    <select name="trade" 
                                            id="trade" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Trade</option>
                                        @foreach(\App\Models\Staff::getTrades() as $key => $tradeName)
                                            <option value="{{ $key }}" {{ old('trade', $staff->trade ?? '') === $key ? 'selected' : '' }}>{{ $tradeName }}</option>
                                        @endforeach
                                    </select>
                                    @error('trade')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Arm of Service -->
                                <div>
                                    <label for="arm_of_service" class="block text-sm font-medium text-gray-700 mb-2">
                                        Arm of Service
                                    </label>
                                    <select name="arm_of_service" 
                                            id="arm_of_service" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Arm of Service</option>
                                        @foreach(\App\Models\Staff::getArmsOfService() as $key => $armName)
                                            <option value="{{ $key }}" {{ old('arm_of_service', $staff->arm_of_service ?? '') === $key ? 'selected' : '' }}>{{ $armName }}</option>
                                        @endforeach
                                    </select>
                                    @error('arm_of_service')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Appointment -->
                                <div>
                                    <label for="appointment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Appointment
                                    </label>
                                    <input type="text" 
                                           name="appointment" 
                                           id="appointment" 
                                           value="{{ old('appointment', $staff->appointment ?? '') }}"
                                           placeholder="e.g., Platoon Commander, Section IC"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('appointment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="deployment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select name="deployment" 
                                            id="deployment" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Status</option>
                                        <option value="On Ground" {{ old('deployment', $staff->deployment ?? '') === 'On Ground' ? 'selected' : '' }}>On Ground</option>
                                        <option value="Leave" {{ old('deployment', $staff->deployment ?? '') === 'Leave' ? 'selected' : '' }}>Leave</option>
                                        <option value="T Leave" {{ old('deployment', $staff->deployment ?? '') === 'T Leave' ? 'selected' : '' }}>T Leave</option>
                                        <option value="Indisposed" {{ old('deployment', $staff->deployment ?? '') === 'Indisposed' ? 'selected' : '' }}>Indisposed</option>
                                        <option value="Mission" {{ old('deployment', $staff->deployment ?? '') === 'Mission' ? 'selected' : '' }}>Mission</option>
                                    </select>
                                    @error('deployment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Enrollment -->
                                <div>
                                    <label for="date_of_enrollment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Enrollment
                                    </label>
                                    <input type="date" 
                                           name="date_of_enrollment" 
                                           id="date_of_enrollment" 
                                           value="{{ old('date_of_enrollment', $staff->date_of_enrollment ? $staff->date_of_enrollment->format('Y-m-d') : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('date_of_enrollment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Present Posting Date -->
                                <div>
                                    <label for="present_posting_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Present Posting
                                    </label>
                                    <input type="date" 
                                           name="present_posting_date" 
                                           id="present_posting_date" 
                                           value="{{ old('present_posting_date', $staff->present_posting_date ? $staff->present_posting_date->format('Y-m-d') : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('present_posting_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Civilian-Specific Fields -->
                        <div id="civilianFields" style="display: none;" class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Civilian Personnel Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Present Grade -->
                                <div>
                                    <label for="present_grade" class="block text-sm font-medium text-gray-700 mb-2">
                                        Present Grade <span class="text-red-500">*</span>
                                    </label>
                                    <select name="present_grade" 
                                            id="present_grade" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Grade</option>
                                        @foreach(\App\Models\Staff::getGrades() as $key => $gradeName)
                                            <option value="{{ $key }}" {{ old('present_grade', $staff->present_grade ?? '') === $key ? 'selected' : '' }}>{{ $gradeName }}</option>
                                        @endforeach
                                    </select>
                                    @error('present_grade')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Staff Category (Senior/Junior) -->
                                <div>
                                    <label for="staff_category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Staff Category
                                    </label>
                                    <select name="staff_category" 
                                            id="staff_category" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        <option value="Senior" {{ old('staff_category', $staff->staff_category ?? '') === 'Senior' ? 'selected' : '' }}>Senior Staff</option>
                                        <option value="Junior" {{ old('staff_category', $staff->staff_category ?? '') === 'Junior' ? 'selected' : '' }}>Junior Staff</option>
                                    </select>
                                    @error('staff_category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Appointment/Job Description -->
                                <div>
                                    <label for="appointment_civ" class="block text-sm font-medium text-gray-700 mb-2">
                                        Appointment/Job Description
                                    </label>
                                    <select name="appointment" 
                                            id="appointment_civ" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Appointment</option>
                                        @foreach(\App\Models\Staff::getJobDescriptions() as $key => $jobDesc)
                                            <option value="{{ $key }}" {{ old('appointment', $staff->appointment ?? '') === $key ? 'selected' : '' }}>{{ $jobDesc }}</option>
                                        @endforeach
                                    </select>
                                    @error('appointment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="deployment_civ" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select name="deployment" 
                                            id="deployment_civ" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Status</option>
                                        <option value="On Ground" {{ old('deployment', $staff->deployment ?? '') === 'On Ground' ? 'selected' : '' }}>On Ground</option>
                                        <option value="Leave" {{ old('deployment', $staff->deployment ?? '') === 'Leave' ? 'selected' : '' }}>Leave</option>
                                        <option value="T Leave" {{ old('deployment', $staff->deployment ?? '') === 'T Leave' ? 'selected' : '' }}>T Leave</option>
                                        <option value="Indisposed" {{ old('deployment', $staff->deployment ?? '') === 'Indisposed' ? 'selected' : '' }}>Indisposed</option>
                                    </select>
                                    @error('deployment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div>
                                    <label for="location_civ" class="block text-sm font-medium text-gray-700 mb-2">
                                        Location
                                    </label>
                                    <select name="location" 
                                            id="location_civ" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Location</option>
                                        <option value="HQ" {{ old('location', $staff->location ?? '') === 'HQ' ? 'selected' : '' }}>HQ</option>
                                        <option value="Admin Building" {{ old('location', $staff->location ?? '') === 'Admin Building' ? 'selected' : '' }}>Admin Building</option>
                                        <option value="Junior Division" {{ old('location', $staff->location ?? '') === 'Junior Division' ? 'selected' : '' }}>Junior Division</option>
                                        <option value="QM" {{ old('location', $staff->location ?? '') === 'QM' ? 'selected' : '' }}>QM</option>
                                        <option value="Research" {{ old('location', $staff->location ?? '') === 'Research' ? 'selected' : '' }}>Research</option>
                                        <option value="Library" {{ old('location', $staff->location ?? '') === 'Library' ? 'selected' : '' }}>Library</option>
                                        <option value="Hamidu Hall" {{ old('location', $staff->location ?? '') === 'Hamidu Hall' ? 'selected' : '' }}>Hamidu Hall</option>
                                    </select>
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of First Appointment -->
                                <div>
                                    <label for="date_of_first_appointment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of First Appointment
                                    </label>
                                    <input type="date" 
                                           name="date_of_first_appointment" 
                                           id="date_of_first_appointment" 
                                           value="{{ old('date_of_first_appointment', $staff->date_of_first_appointment ? $staff->date_of_first_appointment->format('Y-m-d') : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('date_of_first_appointment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Present Posting Date -->
                                <div>
                                    <label for="present_posting_date_civ" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Present Posting
                                    </label>
                                    <input type="date" 
                                           name="present_posting_date" 
                                           id="present_posting_date_civ" 
                                           value="{{ old('present_posting_date', $staff->date_of_posting ? $staff->date_of_posting->format('Y-m-d') : '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('present_posting_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                            <a href="{{ route('staff.index') }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Update Staff Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
        const staffType = '{{ $staff->type }}';
        const militaryFields = document.getElementById('militaryFields');
        const civilianFields = document.getElementById('civilianFields');
        
        if (staffType === 'military') {
            militaryFields.style.display = 'block';
            civilianFields.style.display = 'none';
            
            // Disable civilian fields so they don't submit
            civilianFields.querySelectorAll('input, select, textarea').forEach(field => {
                field.disabled = true;
            });
            
            // Set required fields for military
            const rankField = document.getElementById('rank');
            const sexField = document.getElementById('sex');
            const gradeField = document.getElementById('present_grade');
            
            if (rankField) rankField.setAttribute('required', 'required');
            if (sexField) sexField.setAttribute('required', 'required');
            if (gradeField) gradeField.removeAttribute('required');
        } else if (staffType === 'civilian') {
            militaryFields.style.display = 'none';
            civilianFields.style.display = 'block';
            
            // Disable military fields so they don't submit
            militaryFields.querySelectorAll('input, select, textarea').forEach(field => {
                field.disabled = true;
            });
            
            // Set required fields for civilian
            const gradeField = document.getElementById('present_grade');
            const rankField = document.getElementById('rank');
            const sexField = document.getElementById('sex');
            
            if (gradeField) gradeField.setAttribute('required', 'required');
            if (rankField) rankField.removeAttribute('required');
            if (sexField) sexField.removeAttribute('required');
        }
    });
    </script>
</x-app-layout>