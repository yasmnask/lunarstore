<?php

namespace App\Livewire\Client\Profile;

use App\Models\User;
use App\Services\Client\UserService;
use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{
    public $activeSection = 'overview';

    public ?User $user = null;

    public $stats = [
        'total_orders' => 24,
        'total_spent' => 1250000,
        'active_subscriptions' => 3,
        'wishlist_items' => 12
    ];

    public function mount(String $username, UserService $userService, Request $request)
    {
        // validate query parameter
        $section = $request->query('tab', 'overview');

        $username = ($username != '_g_') ? $username : null;

        if (!in_array($section, ['overview', 'personal'])) {
            $section = 'overview';
        }
        $this->setActiveSection($section);

        $this->user = $userService->getUser($username);

        // Fallback if user data is not found
        if (!$this->user) {
            $this->redirect('/', true);
        }
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
    }

    public function render()
    {
        return view('livewire.client.profile.index', [
            'title' => $this->user?->username ?? 'User Profile'
        ]);
    }
}
