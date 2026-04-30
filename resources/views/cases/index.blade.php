@props([
    'caseRecords' => [],
    'status' => 'ALL',
    'category_id' => 'ALL',
    'search',
    'assigned',
    'users' => []
])
@use('App\CaseRecordsStatus')

<x-layout active="cases">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/cases/create">Create</a>
        </div>
    @endif
    <div class="p-2">
        <form class="text-neutral" method="GET">
            <label class=text-neutral text-sm font-bold mb-2" for="status">Status:
                <select class="select shadow bg-primary text-primary-content w-30" name="status" id="status">
                    <option value="ALL" @selected(old('status', $status) == 'All')>All</option>
                    <option value="{{ CaseRecordsStatus::OPEN->value }}" @selected(old('status', $status) == CaseRecordsStatus::OPEN->value)>{{ CaseRecordsStatus::OPEN->label() }}</option>
                    <option value="{{ CaseRecordsStatus::IN_PROGRESS->value }}" @selected(old('status', $status) == CaseRecordsStatus::IN_PROGRESS->value)>{{ CaseRecordsStatus::IN_PROGRESS->label() }}</option>
                    <option value="{{ CaseRecordsStatus::CLOSED->value }}" @selected(old('status', $status) == CaseRecordsStatus::CLOSED->value)>{{ CaseRecordsStatus::CLOSED->label() }}</option>
                </select>
            </label>

            <label class="text-neutral text-sm font-bold mb-2" for="category_id">Category:
                <select class="select shadow bg-primary text-primary-content w-30" name="category_id" id="category_id">
                    <option value="ALL" @selected(old('category_id', $category_id) == 'All')>All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>

             @if (auth()->user()->role === 'admin')
                <label class="text-neutral text-sm font-bold mb-2" for="worker">Worker:
                    <input class="input shadow bg-primary text-primary-content w-30" type="text" id="worker" list="workers" value="{{ $worker ?? '' }}">
                
                    <datalist id="workers">
                        <option data-id='' value='ALL'>
                        @foreach ($users as $worker)
                            <option data-id='{{ $worker->id }}' value='{{ $worker->name }}'>
                        @endforeach
                    <input type="hidden" name="assigned" id="assigned" value="{{ $assigned }}">
                    <script>
                        document.getElementById('worker').addEventListener('input', function(e) {
                            var input = e.target;
                            var list = input.getAttribute('list');
                            var options = document.querySelectorAll('#' + list + ' option');
                            var hiddenInput = document.getElementById('assigned');
                            var inputValue = input.value;

                            hiddenInput.value = ""; // Reset if no match

                            for (var i = 0; i < options.length; i++) {
                                var option = options[i];
                                if (option.value === inputValue) {
                                    hiddenInput.value = option.getAttribute('data-id');
                                    break;
                                }
                            }
                        });
                    </script>
                
                
                
                </label>
            @endif

            <label class="text-neutral text-sm font-bold mb-2" for="search">Search:
                <input class="input shadow bg-primary text-primary-content w-30" type="text" name="search" value="{{ $search ?? '' }}">
            </label>

            <button class="btn btn-xs btn-accent" type="submit">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-5 gap-2 m-4">
            @foreach ($caseRecords as $caseRecord )
                <x-card.case :caseRecord="$caseRecord"/>
            @endforeach
    </div>
</x-layout>