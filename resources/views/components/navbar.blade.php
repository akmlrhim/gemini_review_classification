<nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700 text-md antialiased">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="{{ url()->current() }}" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 mr-5" alt="Flowbite Logo" />
    </a>
    <button data-collapse-toggle="navbar-dropdown" type="button"
      class="inline-flex items-center p-2 w-10 h-10 justify-center text-md text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
      aria-controls="navbar-dropdown" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M1 1h15M1 7h15M1 13h15" />
      </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
      <ul
        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

        <x-nav-link href="{{ route('dashboard.index') }}">Dashboard</x-nav-link>

        <li>
          <button id="datasetNavbarLink" data-dropdown-toggle="datasetDropdown"
            class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Dataset
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
            </svg></button>
          <div id="datasetDropdown"
            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-2 text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">

              <x-nav-link-dropdown href="{{ route('dataset.index') }}">Full Dataset</x-nav-link-dropdown>
              <x-nav-link-dropdown href="{{ route('dataset.contents') }}">Content only</x-nav-link-dropdown>

            </ul>
          </div>
        </li>

        <li>
          <button id="preproLink" data-dropdown-toggle="preproDropdown"
            class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Preprocessing
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
            </svg></button>
          <div id="preproDropdown"
            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-2 text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">

              <x-nav-link-dropdown href="{{ route('preprocessing.index') }}">Preprosesing</x-nav-link-dropdown>
              <x-nav-link-dropdown href="{{ route('preprosesing.label') }}">Label</x-nav-link-dropdown>
              <x-nav-link-dropdown href="{{ route('preprocessing.tfidf') }}">TF-IDF</x-nav-link-dropdown>

            </ul>
          </div>
        </li>

        <x-nav-link href="{{ route('result.naive-bayes') }}">Naive Bayes Clsf.</x-nav-link>
        <x-nav-link href="{{ route('result.confusion-matrix-form') }}">Conf. Matrix</x-nav-link>

        <li>
          <button id="user" data-dropdown-toggle="userDropdown"
            class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
            &#64;
            {{ Auth::user()->name }}
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
            </svg></button>
          <div id="userDropdown"
            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-2 text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">

              <x-nav-link-dropdown href="#"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">Logout</x-nav-link-dropdown>
            </ul>
          </div>
        </li>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      </ul>
    </div>
  </div>
</nav>
