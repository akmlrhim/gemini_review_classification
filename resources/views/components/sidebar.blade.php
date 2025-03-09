<aside id="logo-sidebar"
  class="fixed top-0 left-0 z-40 w-64 pt-16 h-screen transition-transform -translate-x-full bg-ghost-white sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 shadow-xl"
  aria-label="Sidebar">
  <div class="h-full px-3 pb-4 bg-ghost-white dark:bg-gray-800">
    <ul class="space-y-2 font-medium">
      <li>
        <x-nav-link href="{{ route('dataset') }}" :active="request()->routeIs('dataset')">Dataset</x-nav-link>
        <x-nav-link href="{{ route('dataset') }}" :active="request()->is('')">Clean Data</x-nav-link>
        <x-nav-link href="#"
          onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Logout</x-nav-link>
      </li>
    </ul>
  </div>
</aside>


{{-- form trigger logout  --}}
<form action="{{ route('logout') }}" id="logoutForm" method="POST" class="hidden">
  @csrf
</form>
