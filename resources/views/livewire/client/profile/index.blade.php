<div x-data="{ activeSection: @entangle('activeSection') }">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <x-client.profile.sidebar :user="$user" :stats="$stats" :activeSection="$activeSection" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto bg-gray-50">
            <!-- Overview Section -->
            <div x-show="activeSection === 'overview'">
                @livewire('client.profile.overview', ['user' => $user, 'stats' => $stats])
            </div>

            <!-- Personal Info Section -->
            <div x-show="activeSection === 'personal'">
                @livewire('client.profile.personal-info', ['user' => $user])
            </div>
        </div>
    </div>
</div>
