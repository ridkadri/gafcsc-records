<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assign HOD Role') }}
            </h2>
            <a href="{{ route('hod-management.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Back to HOD Management
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hod-management.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <!-- Info Banner -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    About HOD Assignment
                                </h4>
                                <div class="text-xs text-blue-800 space-y-1.5 ml-7">
                                    <p>• The selected staff member will become Head of their Department</p>
                                    <p>• <strong>All staff in the same department</strong> will be automatically assigned as their subordinates</p>
                                    <p>• The HOD will see their subordinates on their dashboard</p>
                                    <p>• If the staff member doesn't have a user account, one will be created</p>
                                    <p>• Default password will be <strong class="font-mono">gafcsc@123</strong> (must change on first login)</p>
                                    <p>• Only one HOD can be assigned per department</p>
                                </div>
                            </div>

                            <!-- Staff Selection -->
                            <div>
                                <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Staff Member <span class="text-red-500">*</span>
                                </label>
                                
                                @if($staff->count() > 0)
                                    <select id="staff_id" name="staff_id" required 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('staff_id') border-red-500 @enderror"
                                            onchange="updateStaffInfo()">
                                        <option value="">-- Select a staff member --</option>
                                        @foreach($staff->groupBy('department') as $department => $departmentStaff)
                                            <optgroup label="{{ $department }}">
                                                @foreach($departmentStaff as $member)
                                                    <option value="{{ $member->id }}" 
                                                            data-name="{{ $member->name }}"
                                                            data-service="{{ $member->service_number }}"
                                                            data-type="{{ $member->type }}"
                                                            data-department="{{ $member->department }}"
                                                            data-staff-count="{{ \App\Models\Staff::where('department', $member->department)->where('id', '!=', $member->id)->count() }}">
                                                        {{ $member->name }} ({{ $member->service_number }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <h4 class="text-sm font-medium text-yellow-800">No eligible staff found</h4>
                                                <p class="text-sm text-yellow-700 mt-1">
                                                    All staff members either already have HOD roles or don't have departments assigned. 
                                                    Please ensure staff have departments before assigning HOD roles.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @error('staff_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <p class="mt-2 text-xs text-gray-500">
                                    Only staff with assigned departments are shown. Staff are grouped by department.
                                </p>
                            </div>

                            <!-- Selected Staff Info (Dynamic) -->
                            <div id="staffInfo" class="hidden">
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Selected Staff Details</h4>
                                    <dl class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <dt class="text-gray-500">Name</dt>
                                            <dd class="font-medium text-gray-900" id="infoName">-</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Service Number</dt>
                                            <dd class="font-medium text-gray-900" id="infoService">-</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Staff Type</dt>
                                            <dd class="font-medium text-gray-900" id="infoType">-</dd>
                                        </div>
                                        <div>
                                            <dt class="text-gray-500">Department</dt>
                                            <dd class="font-medium text-gray-900" id="infoDepartment">-</dd>
                                        </div>
                                        <div class="col-span-2">
                                            <dt class="text-gray-500">Will Manage</dt>
                                            <dd class="font-medium text-blue-900 flex items-center" id="infoStaffCount">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                                <span id="staffCount">0</span> staff in department
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('hod-management.index') }}" 
                                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="submitBtn"
                                        {{ $staff->count() == 0 ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Assign HOD Role
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStaffInfo() {
            const select = document.getElementById('staff_id');
            const staffInfo = document.getElementById('staffInfo');
            const selectedOption = select.options[select.selectedIndex];
            
            if (select.value) {
                // Show info panel
                staffInfo.classList.remove('hidden');
                
                // Update info
                document.getElementById('infoName').textContent = selectedOption.dataset.name;
                document.getElementById('infoService').textContent = selectedOption.dataset.service;
                document.getElementById('infoType').textContent = selectedOption.dataset.type.charAt(0).toUpperCase() + selectedOption.dataset.type.slice(1);
                document.getElementById('infoDepartment').textContent = selectedOption.dataset.department;
                document.getElementById('staffCount').textContent = selectedOption.dataset.staffCount;
            } else {
                // Hide info panel
                staffInfo.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>