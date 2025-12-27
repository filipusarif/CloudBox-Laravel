<div class="p-6 max-w-7xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">System Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 uppercase font-bold">Total Users</p>
            <h3 class="text-3xl font-black text-blue-600">{{ $totalUsers }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 uppercase font-bold">Total Files</p>
            <h3 class="text-3xl font-black text-indigo-600">{{ $totalFiles }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 uppercase font-bold">Storage Used</p>
            <h3 class="text-xl font-black text-red-600">{{ number_format($usedStorage / (1024**3), 2) }} GB</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 uppercase font-bold">Available</p>
            <h3 class="text-xl font-black text-green-600">{{ number_format($freeStorage / (1024**3), 2) }} GB</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" wire:ignore>
            <h4 class="font-bold mb-4 text-gray-700">Storage Distribution</h4>
            <div class="h-64">
                <canvas id="storageChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" wire:ignore>
            <h4 class="font-bold mb-4 text-gray-700">Top 5 Storage Users</h4>
            <div class="h-64">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Chart 1: Storage
            new Chart(document.getElementById('storageChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Used Storage', 'Free Capacity'],
                    datasets: [{
                        data: [{{ $usedStorage }}, {{ $freeStorage }}],
                        backgroundColor: ['#EF4444', '#10B981'],
                        borderWidth: 0
                    }]
                },
                options: { maintainAspectRatio: false }
            });

            // Chart 2: Top Users
            new Chart(document.getElementById('userChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topUsers->pluck('name')) !!},
                    datasets: [{
                        label: 'Used (Bytes)',
                        data: {!! json_encode($topUsers->pluck('used_capacity')) !!},
                        backgroundColor: '#3B82F6',
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</div>