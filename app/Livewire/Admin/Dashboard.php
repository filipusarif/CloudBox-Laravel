<?php

namespace App\Livewire\Admin;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $usedStorage = User::sum('used_capacity');
        $totalStorage = User::sum('total_capacity');
        $freeStorage = max(0, $totalStorage - $usedStorage);

        $fileDistribution = File::select('extension', DB::raw('count(*) as total'))
            ->groupBy('extension')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $topUsers = User::orderByDesc('used_capacity')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalUsers' => User::count(),
            'totalFiles' => File::count(),
            'usedStorage' => $usedStorage,
            'freeStorage' => $freeStorage,
            'fileDistribution' => $fileDistribution,
            'topUsers' => $topUsers,
        ]);
    }
}
