@props([
    'clients' => [],
    'clientName',
    'clientsAll' => [],
    'client'
])

<x-layout active="clients">
    @if (auth()->user()->role === 'admin')
        <div>
            <a class="btn btn-xs btn-info text-neutral" href="/clients/create">Create</a>
        </div>
    @endif


    <div class="p-2">
        <form class="text-neutral" method="GET">

             @if (auth()->user()->role === 'admin')
                <label class="text-neutral text-sm font-bold mb-2" for="clientsAll">Type to Search Clients:
                    <input class="input shadow bg-primary text-primary-content w-30" type="text" id="clientsAll" list="clientList" value="{{ $clientName ?? '' }}">
                
                    <datalist id="clientList">
                        <option data-id='' value='ALL'>
                        @foreach ($clientsAll as $clientSearch)
                            <option data-id='{{ $clientSearch->id }}' value='{{ $clientSearch->first_name }} {{ $clientSearch->last_name }}'>
                        @endforeach
                    <input type="hidden" name="client" id="client" value="{{ $client }}">
                    <script>
                        document.getElementById('clientsAll').addEventListener('input', function(e) {
                            var input = e.target;
                            var list = input.getAttribute('list');
                            var options = document.querySelectorAll('#' + list + ' option');
                            var hiddenInput = document.getElementById('client');
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

            <button class="btn btn-xs btn-accent" type="submit">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-5 gap-2 m-4">
        @foreach ($clients as $clientInfo )
            <x-card.client :client="$clientInfo"/>
        @endforeach
    </div>

</x-layout>