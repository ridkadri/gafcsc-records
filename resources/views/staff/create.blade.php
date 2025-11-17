<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Staff Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('staff.store') }}" id="staffForm">
                        @csrf

                        <!-- Staff Type Selection -->
                        <div class="mb-8 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Staff Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-{{ !empty($allowedTypes) ? count($allowedTypes) : 1 }} gap-4">
                                @if(isset($allowedTypes['military']))
                                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('type') === 'military' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}" id="militaryLabel">
                                        <input type="radio" 
                                               name="type" 
                                               value="military" 
                                               {{ old('type') === 'military' ? 'checked' : '' }}
                                               {{ !empty($allowedTypes) && count($allowedTypes) === 1 ? 'checked' : '' }}
                                               required
                                               onchange="toggleFields()"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-3">
                                            <span class="block font-medium text-gray-900">🎖️ Military Personnel</span>
                                            <span class="block text-sm text-gray-500">Armed Forces Staff</span>
                                        </span>
                                    </label>
                                @endif
                                
                                @if(isset($allowedTypes['civilian']))
                                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('type') === 'civilian' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}" id="civilianLabel">
                                        <input type="radio" 
                                               name="type" 
                                               value="civilian" 
                                               {{ old('type') === 'civilian' ? 'checked' : '' }}
                                               {{ !empty($allowedTypes) && count($allowedTypes) === 1 && !isset($allowedTypes['military']) ? 'checked' : '' }}
                                               required
                                               onchange="toggleFields()"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-3">
                                            <span class="block font-medium text-gray-900">👔 Civilian Personnel</span>
                                            <span class="block text-sm text-gray-500">Civil Service Staff</span>
                                        </span>
                                    </label>
                                @endif
                            </div>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                       value="{{ old('name') }}"
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
                                        <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
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
                                               {{ old('is_hod') ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_hod" class="font-medium text-gray-700">
                                            This person is the Head of Department (HOD)
                                        </label>
                                        <p class="text-sm text-gray-500">
                                            Check this box to make this staff member the Head of Department for <strong id="selectedDept">their selected department</strong>. All staff in the same department will report to them.
                                        </p>
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
                                       value="{{ old('date_of_birth') }}"
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
                                       value="{{ old('last_promotion_date') }}"
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
                                           value="{{ old('appointment') }}"
                                           placeholder="e.g., Platoon Commander, Section IC"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('appointment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Deployment -->
                                <div>
                                    <label for="deployment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deployment
                                    </label>
                                    <input type="text" 
                                           name="deployment" 
                                           id="deployment" 
                                           value="{{ old('deployment') }}"
                                           placeholder="e.g., Finance Unit, HR Department"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                           value="{{ old('date_of_enrollment') }}"
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
                                           value="{{ old('present_posting_date') }}"
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
                                        <option value="Senior" {{ old('staff_category') === 'Senior' ? 'selected' : '' }}>Senior Staff</option>
                                        <option value="Junior" {{ old('staff_category') === 'Junior' ? 'selected' : '' }}>Junior Staff</option>
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
                                            <option value="{{ $key }}" {{ old('appointment') === $key ? 'selected' : '' }}>{{ $jobDesc }}</option>
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
                                        <option value="On Ground" {{ old('deployment') === 'On Ground' ? 'selected' : '' }}>On Ground</option>
                                        <option value="Leave" {{ old('deployment') === 'Leave' ? 'selected' : '' }}>Leave</option>
                                        <option value="T Leave" {{ old('deployment') === 'T Leave' ? 'selected' : '' }}>T Leave</option>
                                        <option value="Indisposed" {{ old('deployment') === 'Indisposed' ? 'selected' : '' }}>Indisposed</option>
                                    </select>
                                    @error('deployment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                        Location
                                    </label>
                                    <select name="location" 
                                            id="location" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Location</option>
                                        <option value="Accra" {{ old('location') === 'Accra' ? 'selected' : '' }}>Accra</option>
                                        <option value="Kumasi" {{ old('location') === 'Kumasi' ? 'selected' : '' }}>Kumasi</option>
                                        <option value="Tamale" {{ old('location') === 'Tamale' ? 'selected' : '' }}>Tamale</option>
                                        <option value="Takoradi" {{ old('location') === 'Takoradi' ? 'selected' : '' }}>Takoradi</option>
                                        <option value="Cape Coast" {{ old('location') === 'Cape Coast' ? 'selected' : '' }}>Cape Coast</option>
                                        <option value="Ho" {{ old('location') === 'Ho' ? 'selected' : '' }}>Ho</option>
                                        <option value="Sunyani" {{ old('location') === 'Sunyani' ? 'selected' : '' }}>Sunyani</option>
                                        <option value="Wa" {{ old('location') === 'Wa' ? 'selected' : '' }}>Wa</option>
                                        <option value="Bolgatanga" {{ old('location') === 'Bolgatanga' ? 'selected' : '' }}>Bolgatanga</option>
                                        <option value="Koforidua" {{ old('location') === 'Koforidua' ? 'selected' : '' }}>Koforidua</option>
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
                                           value="{{ old('date_of_first_appointment') }}"
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
                                           value="{{ old('present_posting_date') }}"
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
                                Add Staff Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        function toggleFields() {
            const militaryRadio = document.querySelector('input[name="type"][value="military"]');
            const civilianRadio = document.querySelector('input[name="type"][value="civilian"]');
            const militaryFields = document.getElementById('militaryFields');
            const civilianFields = document.getElementById('civilianFields');
            const militaryLabel = document.getElementById('militaryLabel');
            const civilianLabel = document.getElementById('civilianLabel');
            
            // Check if elements exist (they might not if user can only add one type)
            if (!militaryRadio && !civilianRadio) return;
            
            // Update visual styling
            if (militaryRadio && militaryRadio.checked) {
                if (militaryLabel) {
                    militaryLabel.classList.add('border-blue-500', 'bg-blue-50');
                    militaryLabel.classList.remove('border-gray-200');
                }
                if (civilianLabel) {
                    civilianLabel.classList.remove('border-blue-500', 'bg-blue-50');
                    civilianLabel.classList.add('border-gray-200');
                }
                militaryFields.style.display = 'block';
                civilianFields.style.display = 'none';
                
                // Set required fields
                document.getElementById('rank').setAttribute('required', 'required');
                document.getElementById('sex').setAttribute('required', 'required');
                document.getElementById('present_grade').removeAttribute('required');
            } else if (civilianRadio && civilianRadio.checked) {
                if (civilianLabel) {
                    civilianLabel.classList.add('border-blue-500', 'bg-blue-50');
                    civilianLabel.classList.remove('border-gray-200');
                }
                if (militaryLabel) {
                    militaryLabel.classList.remove('border-blue-500', 'bg-blue-50');
                    militaryLabel.classList.add('border-gray-200');
                }
                militaryFields.style.display = 'none';
                civilianFields.style.display = 'block';
                
                // Set required fields
                document.getElementById('present_grade').setAttribute('required', 'required');
                document.getElementById('rank').removeAttribute('required');
                document.getElementById('sex').removeAttribute('required');
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-select if only one option available
            const militaryRadio = document.querySelector('input[name="type"][value="military"]');
            const civilianRadio = document.querySelector('input[name="type"][value="civilian"]');
            
            if (militaryRadio && !civilianRadio) {
                militaryRadio.checked = true;
            } else if (civilianRadio && !militaryRadio) {
                civilianRadio.checked = true;
            }
            
            toggleFields();
        });
    </script>
</x-app-layout>