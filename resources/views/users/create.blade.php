<x-layout active='users'>

<div class="flex items-center justify-center text-base-content">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-2" action="/users" method="POST">
        <h1 class="block text-sm font-bold mb-2">Create New User:</h1>
        @csrf

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="name" name="name" placeholder="Full Name:" required>
            </label>
            @if ($errors->has('name'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('name') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" id="email" name="email" placeholder="Email:" required>
            </label>
            @if ($errors->has('email'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('email') }} </div>
            @endif

            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" id="password" name="password" placeholder="Password:" required>
            </label>
            @if ($errors->has('password'))
                <div class="alert alert-outline max-sm:alert-vertical alert-error text-xs font-bold m-1"> {{ $errors->first('password') }} </div>
            @endif

        <button class="btn btn-soft btn-primary" type="submit">Create User</button>
    </form>
</div>

</x-layout>
   