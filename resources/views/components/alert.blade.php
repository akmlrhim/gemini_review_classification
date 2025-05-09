@if (session('success'))
  <div class="text-sm p-2 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    <span class="font-medium">{{ session('success') }} </span>
  </div>
@endif

@if (session('error'))
  <div class="text-sm p-2 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <span class="font-medium">{{ session('error') }}</span>
  </div>
@endif

@if (session('info'))
  <div class="text-sm p-2 mb-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
    <span class="font-medium">{{ session('info') }}</span>
  </div>
@endif
