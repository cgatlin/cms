@props([
    'status' => 'ALL',
    'category_id' => 'ALL',
])
@use('App\CaseRecordsStatus')

<x-layout active="reports">


    <div class="p-2">
        <form class="list text-neutral items-center justify-center" method="POST">
            <label class="text-neutral text-sm font-bold mb-2" for="status">Status:
                <select class="select shadow bg-primary text-primary-content" name="status" id="status">
                    <option value="ALL" @selected(old('status', $status) == 'All')>All</option>
                    <option value="{{ CaseRecordsStatus::OPEN->value }}" @selected(old('status', $status) == CaseRecordsStatus::OPEN->value)>{{ CaseRecordsStatus::OPEN->label() }}</option>
                    <option value="{{ CaseRecordsStatus::IN_PROGRESS->value }}" @selected(old('status', $status) == CaseRecordsStatus::IN_PROGRESS->value)>{{ CaseRecordsStatus::IN_PROGRESS->label() }}</option>
                    <option value="{{ CaseRecordsStatus::CLOSED->value }}" @selected(old('status', $status) == CaseRecordsStatus::CLOSED->value)>{{ CaseRecordsStatus::CLOSED->label() }}</option>
                </select>
            </label>

            <label class="text-neutral text-sm font-bold mb-2" for="category_id">Category:
                <select class="select shadow bg-primary text-primary-content" name="category_id" id="category_id">
                    <option value="ALL" @selected(old('category_id', $category_id) == 'All')>All</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>

             
            <label class="text-neutral text-sm font-bold mb-2" for="worker">Worker:
                <input class="input shadow bg-primary text-primary-content" type="text" id="worker" list="workers" value="{{ $worker ?? '' }}">
            
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

            <label class="text-neutral text-sm font-bold mb-2" for="search">Date Start:
                <input class="input shadow bg-primary text-primary-content" type="date" name="date_start" value="{{ $date_start ?? '' }}">
            </label>

            <label class="text-neutral text-sm font-bold mb-2" for="search">Date End:
                <input class="input shadow bg-primary text-primary-content" type="date" name="date_end" value="{{ $date_end ?? '' }}">
            </label>

            <button class="btn btn-xs btn-accent" type="submit">Generate Report</button>
        </form>
    </div>
</x-layout>