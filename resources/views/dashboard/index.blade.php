@props([
    'caseCount' => [],
    'CaseRecordsRecent' => [],
    'labels',
    'data',
])
@use('App\CaseRecordsStatus')

<x-layout active="dashboard">

<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content items-center justify-center">
        <!-- Page content here -->
         @if (auth()->user()->role === 'admin')
            <p>Recently Updated Cases:</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($CaseRecordsRecent as $caseRecord )
                    <x-card.case :caseRecord="$caseRecord"/>
                @endforeach
            </div>
         @else
            <p>Recently Updated Cases:</p>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($CaseRecordsRecent as $caseRecord )
                    <x-card.case :caseRecord="$caseRecord"/>
                @endforeach
            </div>
         @endif
        
        <div class="grid grid-cols-2 gap-2">

            <div>
                <div class="w-full h-60 p-2">
                    <h6 class="text-center">Cases Status</h6>
                    <canvas id="caseChart"></canvas>
                </div>
            </div>


            <div>
                <div class="w-full h-60 p-2">
                    <h6 class="text-center">Cases Distribution</h6>
                    <canvas class="" id="casePieChart"></canvas>
                </div>
            </div>


        </div>
        <label for="my-drawer-3" class="btn drawer-button lg:hidden">
        Show
        </label>
  </div>
  <div class="drawer-side p-4">
        <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu min-h-full w-25 text-sm">
            <!-- Sidebar content here -->
            <li>
                <div class="stat-card">
                    <h3>Total Cases</h3>
                    <p>{{ $caseCount->total_count }}</p>
                </div>
            </li>
            <li>
                <div class="stat-card">
                    <h3>{{ CaseRecordsStatus::OPEN->label() }} Cases</h3>
                    <p>{{ $caseCount->open_count }}</p>
                </div>
            </li>
            <li>
                <div class="stat-card">
                    <h3>{{ CaseRecordsStatus::IN_PROGRESS->label() }}</h3>
                    <p>{{ $caseCount->progress_count }}</p>
                </div>
            </li>
            <li>
                <div class="stat-card">
                    <h3>{{ CaseRecordsStatus::CLOSED->label() }}</h3>
                    <p>{{ $caseCount->closed_count }}</p>
                </div>
            </li>
        </ul>
  </div>
</div>


<script>
            const labels = @json($labels);
            const data = @json($data);

            // Bar Chart
            new Chart(document.getElementById('caseChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cases',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 133, 0.8)',    // First bar
                            'rgba(54, 162, 235, 0.8)',   // Second bar
                            'rgba(75, 192, 192, 0.8)'   // Third bar
                            ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Pie Chart
            new Chart(document.getElementById('casePieChart'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',    // First bar
                            'rgba(54, 162, 235, 0.8)',   // Second bar
                            'rgba(75, 192, 192, 0.8)'   // Third bar
                            ],
                            hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>

</x-layout>