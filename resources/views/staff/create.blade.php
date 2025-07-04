<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Staff Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('staff.store') }}">
                        @csrf

                        <!-- Rank/Title -->
                        <div class="mb-6">
                            <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">
                                Rank/Title
                            </label>
                            <input type="text" 
                                   id="rank" 
                                   name="rank" 
                                   value="{{ old('rank') }}"
                                   placeholder="e.g., Colonel, Professor, Dr, Mr, Mrs, etc."
                                   list="rank-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('rank') border-red-500 @enderror">
                            
                            <!-- Datalist for rank suggestions -->
                            <datalist id="rank-suggestions">
                                <!-- Military Ranks -->
                                <option value="General">
                                <option value="Brigadier General">
                                <option value="Colonel">
                                <option value="Lieutenant Colonel">
                                <option value="Major">
                                <option value="Captain">
                                <option value="Lieutenant">
                                <option value="Second Lieutenant">
                                <option value="Sergeant Major">
                                <option value="Sergeant">
                                <option value="Corporal">
                                <option value="Private">
                                <!-- Civilian Titles -->
                                <option value="Professor">
                                <option value="Associate Professor">
                                <option value="Assistant Professor">
                                <option value="Senior Lecturer">
                                <option value="Lecturer">
                                <option value="Dr">
                                <option value="Mr">
                                <option value="Mrs">
                                <option value="Ms">
                                <option value="Miss">
                                @if(isset($existingRanks))
                                    @foreach($existingRanks as $existingRank)
                                        <option value="{{ $existingRank }}">
                                    @endforeach
                                @endif
                            </datalist>
                            
                            @error('rank')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Military rank or civilian title (e.g., Colonel, Professor, Dr)</p>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name *
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

                        <!-- Service Number -->
                        <div class="mb-6">
                            <label for="service_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Number *
                            </label>
                            <input type="text" 
                                   id="service_number" 
                                   name="service_number" 
                                   value="{{ old('service_number') }}"
                                   required
                                   placeholder="e.g., MIL001, CIV001, etc."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('service_number') border-red-500 @enderror">
                            @error('service_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Unique identifier for both military and civilian staff</p>
                        </div>

                        <!-- Staff Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Staff Type *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="staff_type" 
                                           value="military" 
                                           {{ old('staff_type') === 'military' ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                    <span class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">Military Personnel</span>
                                        <span class="block text-gray-500">Active military staff (commissioned and non-commissioned officers)</span>
                                    </span>
                                </label>
                                
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="staff_type" 
                                           value="civilian" 
                                           {{ old('staff_type', 'civilian') === 'civilian' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">Civilian Personnel</span>
                                        <span class="block text-gray-500">Academic and administrative civilian staff</span>
                                    </span>
                                </label>
                            </div>
                            @error('staff_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        

                        <!-- Appointment -->
                        <div class="mb-6">
                            <label for="appointment" class="block text-sm font-medium text-gray-700 mb-2">
                                Appointment
                            </label>
                            <input type="text" 
                                   id="appointment" 
                                   name="appointment" 
                                   value="{{ old('appointment') }}"
                                   placeholder="e.g., Commandant, Dean, Head of Department, etc."
                                   list="appointment-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('appointment') border-red-500 @enderror">
                            
                            <!-- Datalist for appointment suggestions -->
                            <datalist id="appointment-suggestions">
                                <option value="Commandant">
                                <option value="Deputy Commandant">
                                <option value="Assistant Commandant (Snr Div)">
                                <option value="Assistant Commandant (Jnr Div)">
                                <option value="C COORD">
                                <option value="Dir Corporate Affairs">
                                <option value="Dir International Staff & Students">
                                <option value="Dir R&D">
                                <option value="GSOI Coord (Snr Div)">
                                <option value="GSOI Coord (Jnr Div)">
                                <option value="CO">
                                <option value="Financial Comptroller">
                                <option value="CPRO">
                                <option value="COMD Legal Officer">
                                <option value="IT Manager">
                                <option value="DD Corp Affairs">
                                <option value="GSO II (A&Q)">
                                <option value="GSO II Coord (Snr Div)">
                                <option value="GSO II Coord (Jnr Div)">
                                <option value="Comd PRO">
                                <option value="Admin Officer">
                                <option value="ADC to Cmdt">
                                <option value="GSO II Coord (Snr Div)">
                                <option value="Chief Instructor">
                                <option value="Directing Staff (Snr Div)">
                                <option value="Directing Staff (Jnr Div)">
                                <!---- Civilian Appointments ----->
                                <option value="Dean">
                                <option value="Associate Dean">
                                <option value="Senior Research Fellow">
                                <option value="Director">
                                <option value="Registrar">
                                <option value="Librarian">
                                @if(isset($existingAppointments))
                                    @foreach($existingAppointments as $existingAppointment)
                                        <option value="{{ $existingAppointment }}">
                                    @endforeach
                                @endif
                            </datalist>
                            
                            @error('appointment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Official position or appointment within the university</p>
                        </div>

                        <!-- Department -->
                        <div class="mb-6">
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Department *
                            </label>
                            <input type="text" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department') }}"
                                   required
                                   placeholder="e.g., Command, Military Studies, Administration, etc."
                                   list="department-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('department') border-red-500 @enderror">
                            
                            <!-- Datalist for department suggestions -->
                            <datalist id="department-suggestions">
                                <option value="Command">
                                <option value="Military Studies">
                                <option value="Teaching Class">
                                <option value="Registry">
                                <option value="Graphic & Map">
                                <option value="Stores">
                                <option value="Audio Visual">
                                <option value="Tradesmen">
                                <option value="Catering/Mess/Bar/Kitchen">
                                <option value="Labourers">
                                <option value="Technical (Printing)">
                                <option value="Gardener">
                                <option value="Sanitation">
                                <option value="Messenger">
                                <option value="Wardens">
                                <option value="Finance">
                                <option value="Corporate Affairs">
                                <option value="Information Technology">
                                <option value="Library">
                                <option value="Research">
                                <option value="Administration">
                                <option value="Secretarial">
                                @if(isset($existingDepartments))
                                    @foreach($existingDepartments as $existingDepartment)
                                        <option value="{{ $existingDepartment }}">
                                    @endforeach
                                @endif
                            </datalist>
                            
                            @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location') }}"
                                   placeholder="e.g., HQ, Junior Division, Main Campus, etc."
                                   list="location-suggestions"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror">
                            
                            <!-- Datalist for location suggestions -->
                            <datalist id="location-suggestions">
                                <option value="HQ">
                                <option value="Junior Division">
                                <option value="Research Block">
                                <option value="Admin Block">
                                <option value="Library">
                                <option value="Dep CMDT Block">
                                <option value="DS Block">
                                <option value="Airforce Block">
                                <option value="MT Yard">
                                @if(isset($existingLocations))
                                    @foreach($existingLocations as $existingLocation)
                                        <option value="{{ $existingLocation }}">
                                    @endforeach
                                @endif
                            </datalist>
                            
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Physical location or campus area</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
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
    </div>
</x-app-layout>