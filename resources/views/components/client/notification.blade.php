<div id="notification"
    class="fixed top-4 right-4 z-[99999] hidden transform transition-transform duration-300 ease-in-out" wire:ignore>
    <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div id="notification-icon" class="w-6 h-6 rounded-full flex items-center justify-center">
                    <!-- Icon will be inserted by JavaScript -->
                </div>
            </div>
            <div class="ml-3">
                <p id="notification-message" class="text-sm font-medium text-gray-900">
                    <!-- Message will be inserted by JavaScript -->
                </p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button id="notification-close" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notification-icon');
            const messageEl = document.getElementById('notification-message');
            const closeBtn = document.getElementById('notification-close');

            // scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Set message
            messageEl.textContent = message;

            // Set icon and colors based on type
            if (type === 'success') {
                icon.className =
                    'w-6 h-6 rounded-full flex items-center justify-center bg-green-100 text-green-600';
                icon.innerHTML = '<i class="fas fa-check text-xs"></i>';
            } else if (type === 'error') {
                icon.className =
                    'w-6 h-6 rounded-full flex items-center justify-center bg-red-100 text-red-600';
                icon.innerHTML = '<i class="fas fa-times text-xs"></i>';
            }

            // Show notification
            notification.classList.remove('hidden');

            // Auto hide after 4 seconds
            setTimeout(() => {
                hideNotification();
            }, 4000);

            // Close button handler
            closeBtn.onclick = hideNotification;
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.add('hidden');
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-error', (message) => {
                showNotification(message.message, 'error');
            });
            Livewire.on('show-success', (message) => {
                showNotification(message.message, 'success');
            });
        });
    });
</script>
