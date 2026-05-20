<div class="navbar bg-base-100 text-base-content shadow-sm p-4">
  <div class="flex-1"> 
    <a class="btn btn-ghost text-xl" href="/">
      <div class="w-auto h-20 overflow-hidden">
        <img src="{{ asset('img/logo.png') }}" 
          class="w-full h-full object-scale-down object-center"
          alt="Logo">
      </div>
      <div>
        <p>Imperial Valley Family Services</p>
      </div>
    </a>
  </div>
  <div class="flex-none">
    <ul class="menu menu-horizontal items-center justify-center px-1">
        @guest
            <li><a class="btn btn-soft btn-secondary" href="/login">Login</a></li>
            {{-- <li><a class="btn btn-soft btn-secondary" href="/register">Sign up</a></li> --}}
        @endguest
        @auth
            <li>
              <a class="btn btn-soft btn-secondary btn-sm" href="/notifications">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /> </svg>
                Messages
                <span class="badge badge-info badge-xs">{{ auth()->user()->unreadNotifications->count() }}</span>
              </a>
            </li>

            <li>
                <form action="/logout" method="POST" id="logout">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-soft btn-secondary btn-sm" type="submit">Logout</button>
                </form>
            </li>   
         @endauth
    </ul>
  </div>
</div>