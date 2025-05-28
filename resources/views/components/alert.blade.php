@if (session('success'))
  <div class="max-w-5xl flex justify-center items-center mx-auto">
    <div class="w-full max-w-5xl text-center">
      <div class="text-sm p-2 mb-2 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
        role="alert">
        <span class="font-medium">{{ session('success') }} </span>
      </div>
    </div>
  </div>
@endif

@if (session('error'))
  <div class="max-w-5xl flex justify-center items-center mx-auto">
    <div class="w-full max-w-5xl text-center">
      <div class="text-sm p-2 mb-2 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ session('error') }}</span>
      </div>
    </div>
  </div>
@endif

@if (session('info'))
  <div class="max-w-5xl flex justify-center items-center mx-auto">
    <div class="w-full max-w-5xl text-center">
      <div class="text-sm p-2 mb-2 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
        role="alert">
        <span class="font-medium">{{ session('info') }}</span>
      </div>
    </div>
  </div>
@endif
