<x-layout title='FIT - Login'>
    <div class="flex items-center justify-center bg-gray-100">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-2" action="/login" method="POST">
        <h1 class="block text-sm font-bold mb-2">Login:</h1>
        @csrf

        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" id="email" name="email" placeholder="Email:" required>
        </label>
        @if ($errors->has('email'))
            <div> {{ $errors->first('email') }} </div>
        @endif



        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" id="password" name="password" placeholder="Password:" required>
        </label>
        @if ($errors->has('password'))
            <div> {{ $errors->first('password') }} </div>
        @endif

        <button class="btn btn-soft btn-secondary" type="submit">Login</button>
    </form>
</div>
</x-layout>
   