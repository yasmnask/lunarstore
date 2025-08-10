<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Personal Information</h1>
        <p class="text-gray-600">Update your personal details and contact information.</p>

        @if ($isWarningAuth)
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 my-4">
                <div class="flex">
                    <div class="flex-shrink-0 self-center">
                        <svg class="h-8 w-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-md text-amber-700 font-semibold">
                            Your account has been linked with Google.
                        </p>
                        <p class="text-md text-amber-700 mt-1">
                            Please change <a href="#personal-info-form"
                                class="text-amber-600 hover:text-amber-800 font-medium">your
                                username</a> and <a href=""
                                class="text-amber-600 hover:text-amber-800 font-medium">change your
                                password</a>.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <form wire:submit="save">
        <div class="bg-white rounded-2xl border border-gray-200 p-8" id="personal-info-form">
            <!-- Profile Image Upload Section -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">Profile Picture</label>
                <div class="flex items-start space-x-6">
                    <!-- Image Preview -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-24 h-24 rounded-xl border-2 border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center">
                            @if ($imagePreview)
                                <img src="{{ is_string($imagePreview) ? $imagePreview : $imagePreview->temporaryUrl() }}"
                                    alt="Preview" class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="flex-1" x-data="{
                        isDragging: false
                    }" @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))">
                        <div class="border-2 border-dashed rounded-xl p-6 text-center transition-all cursor-pointer"
                            :class="isDragging ? 'border-blue-400 bg-blue-50' :
                                'border-gray-300 hover:border-blue-400 hover:bg-blue-50'"
                            @click="$refs.fileInput.click()">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-medium text-blue-600">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                        <input type="file" x-ref="fileInput" wire:model="avatar" accept="image/*" class="hidden">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Username</label>
                    <input id="username" type="text" wire:model="username"
                        {{ $username && trim($username) != '_g_' ? 'disabled' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Email Address</label>
                    <input type="email" wire:model="email" disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Phone Number</label>
                    <input type="tel" wire:model="phone"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Address</label>
                    <textarea rows="3" wire:model="address"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-4 justify-end items-center">
                <button type="button" wire:click="cancelSave"
                    class="text-gray-600 hover:text-gray-800 hover:bg-gray-100 px-6 py-3 rounded-xl transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-colors font-medium">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
