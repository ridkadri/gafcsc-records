<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Profile Information - Everyone --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Date of Birth - For Viewers (who login with DOB) --}}
            @if(Auth::user()->isViewer())
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Update Date of Birth') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Your date of birth is used to log in. If it was entered incorrectly, you can update it here.') }}
                            </p>
                        </header>

                        <form method="POST" action="{{ route('profile.update-dob') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('PATCH')

                            <!-- Current Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Current Date of Birth') }}
                                </label>
                                <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-gray-900 font-semibold">
                                        {{ Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('F j, Y') : 'Not set' }}
                                    </p>
                                </div>
                            </div>

                            <!-- New Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                                    {{ __('New Date of Birth') }}
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}"
                                       required>
                                @error('date_of_birth')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    ⚠️ {{ __('Make sure this is correct - you\'ll use it to log in!') }}
                                </p>
                            </div>

                            <!-- Current Password Confirmation -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    {{ __('Current Password') }}
                                </label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Enter your current password"
                                       required>
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ __('For security, enter your current password to confirm this change.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Update Date of Birth') }}
                                </button>

                                <p class="text-xs text-yellow-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('You\'ll be logged out after updating') }}
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Update Password - Only for non-viewers with email --}}
            @if(!Auth::user()->isViewer() && Auth::user()->email)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            @else
                {{-- Info message for viewers --}}
                @if(Auth::user()->isViewer() && !Auth::user()->email)
                    <div class="p-4 sm:p-8 bg-blue-50 border border-blue-200 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        {{ __('Login Information') }}
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>
                                            {{ __('You login using your service number and date of birth. No email or password is required for your account.') }}
                                        </p>
                                        @if(Auth::user()->date_of_birth && Auth::user()->service_number)
                                            <div class="mt-3 p-3 bg-blue-100 rounded">
                                                <p class="font-semibold text-blue-900">{{ __('Your Login Credentials:') }}</p>
                                                <p class="mt-1"><strong>{{ __('Service Number:') }}</strong> {{ Auth::user()->service_number }}</p>
                                                <p><strong>{{ __('Date of Birth:') }}</strong> {{ \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('F j, Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Delete Account - Only for Super Admin --}}
            @if(Auth::user()->isSuperAdmin())
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>