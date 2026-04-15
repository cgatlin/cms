<div class="navbar bg-base-100 text-base-content shadow-sm p-4">
  <div class="flex-1"> 
    <a class="btn btn-ghost text-xl" href="/">
      <div class="w-auto h-20 overflow-hidden">
        <img src="{{ asset('build/assets/img/logo.png') }}" 
          class="w-full h-full object-scale-down object-center"
          alt="Logo">
      </div>
      <div>
        <p>Imperial Valley Family Services</p>
      </div>
    </a>
  </div>
  <div class="flex-none">
    <ul class="menu menu-horizontal px-1">
        @guest
            <li><a class="btn btn-soft btn-secondary" href="/login">Login</a></li>
            <li><a class="btn btn-soft btn-secondary" href="/register">Sign up</a></li>
        @endguest
        @auth
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