<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assign Subordinates to HOD') }}
            </h2>
            <a href="{{ route('hod-management.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Back to HOD Management
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- HOD Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                {{ substr($hod->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $hod->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $hod->service_number }}</p>
                                <p class="text-sm text-gray-600">{{ $hod->department ?? 'No Department' }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('hod-management.update-subordinates', $hod) }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Select Subordinates (Staff in the same department)
                                </label>

                                @if($availableStaff->count() > 0)
                                    <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                                        @foreach($availableStaff as $staff)
                                            <label class="relative flex items-start p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                <div class="flex items-center h-5">
                                                    <input 
                                                        type="checkbox" 
                                                        name="subordinates[]" 
                                                        value="{{ $staff->id }}"
                                                        {{ in_array($staff->id, $currentSubordinates->pluck('id')->toArray()) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    >
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <span class="block text-sm font-medium text-gray-900">
                                                        {{ $staff->name }}
                                                    </span>
                                                    <span class="block text-xs text-gray-500 mt-1">
                                                        {{ $staff->service_number }} • 
                                                        {{ ucfirst($staff->type) }} • 
                                                        {{ $staff->appointment ?? 'No Appointment' }}
                                                    </span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 border border-gray-200 rounded-lg">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No staff available</h3>
                                        <p class="mt-1 text-sm text-gray-500">There are no other staff members in the same department to assign.</p>
                                    </div>
                                @endif

                                @error('subordinates')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">About Subordinates</h4>
                                <div class="text-xs text-blue-800 space-y-1">
                                    <p>• Only staff members in the same department as the HOD are shown.</p>
                                    <p>• Subordinates will appear on the HOD's dashboard.</p>
                                    <p>• The HOD will be able to view the profiles of their subordinates.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('hod-management.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Assign Subordinates
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>