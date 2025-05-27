<div class="flex items-center justify-center gap-3 max-w-xl mx-auto p-3">
  <form class="flex-grow" action="{{ route('dataset.search') }}" method="GET">
    <label for="simple-search" class="sr-only">Search</label>
    <div class="relative">
      <input type="text" id="search" autocomplete="off"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Masukkan kata kunci...." name="search" />
      <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-black" aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
      </svg>
    </div>
  </form>

  <div class="inline-flex rounded-md shadow-xs" role="group">
    <form action="{{ route('dataset.delete.all') }}" method="POST" onsubmit="return confirm('Apakah anda yakin?');">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="px-4 py-2 text-sm font-medium text-red-500 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100">
        Delete All
      </button>
    </form>

    <button type="button" onclick="window.location.href='{{ route('dataset.index') }}'"
      class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100">
      Back
    </button>
  </div>
</div>
