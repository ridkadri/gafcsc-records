<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('GAFCSC Staff Directory') }}
            </h2>
            @if(Auth::user()->canManageStaff())
                <a href="{{ route('staff.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add New Staff
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Permission Notice for Viewers -->
            @if(Auth::user()->isViewer())
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                You have <strong>View-only</strong> access. Contact an administrator to request edit permissions.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tabbed View for HOD + Admin Users --}}
            @if(Auth::user()->isHeadOfDepartment() && Auth::user()->hasAdminPrivileges())
                @php
                    $currentView = request('view', 'all'); // 'all' or 'department'
                    $hodStaff = Auth::user()->staff;
                    $departmentCount = 0;
                    
                    if ($hodStaff && $hodStaff->is_hod) {
                        $departmentCount = \App\Models\Staff::where('head_of_department_id', $hodStaff->id)->count();
                    }
                @endphp

                <div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px" aria-label="Tabs">
                            {{-- My Department Tab --}}
                            <a href="{{ route('staff.index', ['view' => 'department'] + request()->except('view')) }}" 
                               class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $currentView === 'department' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="mr-2 h-5 w-5 {{ $currentView === 'department' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                My Department
                                @if($departmentCount > 0)
                                    <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium {{ $currentView === 'department' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $departmentCount }}
                                    </span>
                                @endif
                            </a>

                            {{-- All Staff Tab --}}
                            <a href="{{ route('staff.index', ['view' => 'all'] + request()->except('view')) }}" 
                               class="group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $currentView === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="mr-2 h-5 w-5 {{ $currentView === 'all' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                All Staff
                                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium {{ $currentView === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $staff->total() }}
                                </span>
                            </a>
                        </nav>
                    </div>

                    {{-- Context Banner --}}
                    @if($currentView === 'department')
                        <div class="bg-blue-50 px-6 py-3 border-b border-blue-100">
                            <div class="flex items-center text-sm text-blue-700">
                                <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    Viewing staff in <strong>{{ $hodStaff->department ?? 'your department' }}</strong> where you are the Head of Department.
                                    @if($departmentCount === 0)
                                        <span class="ml-1 text-blue-600">No staff assigned yet.</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Search and Filter Section -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('staff.index') }}" class="space-y-4">
                        <!-- Search Input -->
                        <div class="w-full">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, service number, rank, trade, department..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <!-- Filter Row -->
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Type Filter -->
                            <div>
                                <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Types</option>
                                    @foreach($types as $key => $value)
                                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div>
                                <select name="department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>
                                            {{ $department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rank/Grade Filter (Military & Civilian) -->
                            <div>
                                <select name="rank" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Ranks/Grades</option>
                                    
                                    @if(Auth::user()->isChiefClerk())
                                        {{-- Chief Clerk sees only military ranks --}}
                                        @foreach($ranks as $key => $rank)
                                            <option value="{{ $key }}" {{ request('rank') === $key ? 'selected' : '' }}>
                                                {{ $rank }}
                                            </option>
                                        @endforeach
                                    @elseif(Auth::user()->isCapo() || Auth::user()->isPeo())
                                        {{-- CAPO/PEO see only civilian grades --}}
                                        <optgroup label="Civilian Grades">
                                            @foreach(\App\Models\Staff::getCivilianGrades() as $key => $grade)
                                                <option value="{{ $grade }}" {{ request('rank') === $grade ? 'selected' : '' }}>
                                                    {{ $grade }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        {{-- Super Admin & Military Admin see both --}}
                                        <optgroup label="Military Ranks">
                                            @foreach($ranks as $key => $rank)
                                                <option value="{{ $key }}" {{ request('rank') === $key ? 'selected' : '' }}>
                                                    {{ $rank }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Civilian Grades">
                                            @foreach(\App\Models\Staff::getCivilianGrades() as $key => $grade)
                                                <option value="{{ $grade }}" {{ request('rank') === $grade ? 'selected' : '' }}>
                                                    {{ $grade }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>

                            <!-- Deployment/Location Filter (For All Staff) -->
                            <div>
                                <select name="deployment" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @if(Auth::user()->isCapo() || Auth::user()->isPeo())
                                        <option value="">All Locations</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location }}" {{ request('deployment') === $location ? 'selected' : '' }}>
                                                {{ $location }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">All Deployments</option>
                                        @foreach($deployments as $key => $deploy)
                                            <option value="{{ $key }}" {{ request('deployment') === $key ? 'selected' : '' }}>
                                                {{ $deploy }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Search
                                </button>
                                
                                @if(request()->hasAny(['search', 'type', 'department', 'rank', 'deployment', 'location']))
                                    <a href="{{ route('staff.index') }}" 
                                       class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Export Section -->
                @if($staff->count() > 0 && Auth::user()->canViewStaff())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                Export {{ $staff->total() }} staff member(s)
                                @if(request()->hasAny(['search', 'type', 'department', 'rank', 'deployment']))
                                    (filtered results)
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('staff.preview.pdf', request()->all()) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-purple-300 shadow-sm text-sm leading-4 font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview PDF
                                </a>
                                <a href="{{ route('staff.export.csv', request()->all()) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    CSV
                                </a>
                                <a href="{{ route('staff.export.pdf', request()->all()) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Staff Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Staff Member
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rank/Grade
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Department
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <!-- Remove the Actions column header -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staff as $member)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('staff.show', $member) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full {{ $member->isMilitary() ? 'bg-gradient-to-r from-green-600 to-green-800' : 'bg-gradient-to-r from-blue-500 to-purple-600' }} flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">
                                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <!-- Make the name clickable -->
                                                <a href="{{ route('staff.show', $member) }}" 
                                                class="text-sm font-medium text-gray-900 hover:text-blue-600 block">
                                                    {{ $member->name }}
                                                </a>
                                                @if($member->isMilitary())
                                                    @if($member->sex)
                                                        <div class="text-xs text-gray-500">{{ $member->sex }}</div>
                                                    @endif
                                                @else
                                                    @if($member->location)
                                                        <div class="text-xs text-gray-500">📍 {{ $member->location }}</div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                        {{ $member->service_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->isMilitary())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🎖️ Military
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                👔 Civilian
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($member->isMilitary())
                                            @if($member->rank)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $member->rank }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">No rank</span>
                                            @endif
                                        @else
                                            @if($member->present_grade)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $member->present_grade }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">No grade</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->department ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->isMilitary() && $member->deployment)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $member->deployment }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <!-- Remove the Actions column completely -->
                                </tr>
                            @empty
                                <tr>
                                    <!-- Update colspan from 7 to 6 since we removed Actions column -->
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No staff found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if(request()->hasAny(['search', 'type', 'department', 'rank', 'deployment']))
                                                Try adjusting your search criteria.
                                            @else
                                                Get started by adding your first staff member.
                                            @endif
                                        </p>
                                        @if(Auth::user()->canManageStaff() && !request()->hasAny(['search', 'type', 'department', 'rank', 'deployment']))
                                            <div class="mt-6">
                                                <a href="{{ route('staff.create') }}" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    Add Staff Member
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($staff->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $staff->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>