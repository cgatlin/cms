<x-layout active='clients'>

<div class="flex items-center justify-center text-base-content">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-2" action="/clients" method="POST">
        <h1 class="block text-sm font-bold mb-2">Create New Client:</h1>
        @csrf

            <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="first_name" name="first_name" placeholder="First Name:" required>
            </label>
            @if ($errors->has('first_name'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('first_name') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="last_name" name="last_name" placeholder="Last Name:" required>
            </label>
            @if ($errors->has('last_name'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('last_name') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" id="email" name="email" placeholder="Email:">
            </label>
            @if ($errors->has('email'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('email') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="phone" id="phone" name="phone" placeholder="Phone:">
            </label>
            @if ($errors->has('phone'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('phone') }} </div>
            @endif

        <button class="btn btn-soft btn-primary" type="submit">Create User</button>
    </form>
</div>

</x-layout>
   