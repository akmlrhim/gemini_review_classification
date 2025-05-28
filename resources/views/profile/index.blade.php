<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <div class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <form action="{{ route('my-profile.update') }}" method="POST" novalidate>
      @csrf
      @method('PATCH')

      <div class="border-b border-gray-900/10 pb-6">
        <h2 class="text-base font-semibold text-gray-900">Personal Info</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui data pribadi Anda!</p>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
            <input type="email" name="email" id="email" placeholder="Masukkan email"
              value="{{ old('email', Auth::user()->email) }}" autocomplete="off"
              class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm 
                 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500" />
            @error('email')
              <small class="mt-1 text-sm text-red-600">{{ $message }}</small>
            @enderror
          </div>

          <div class="sm:col-span-4">
            <label for="name" class="block text-sm font-medium text-gray-900">Nama</label>
            <input type="text" name="name" id="name" placeholder="Masukkan nama" autocomplete="off"
              value="{{ old('name', Auth::user()->name) }}"
              class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm 
                 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500" />
            @error('name')
              <small class="mt-1 text-sm text-red-600">{{ $message }}</small>
            @enderror
          </div>
        </div>

        <div class="mt-6 flex items-center justify-start">
          <button type="submit"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500
               focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
            Simpan
          </button>
        </div>
      </div>
    </form>


    {{-- PASSWORD  --}}
    <div class="border-b border-gray-900/10 pb-6">
      <h2 class="text-base font-semibold text-gray-900 mt-6">Edit Password</h2>
      <p class="mt-1 text-sm text-gray-600">Silakan perbarui password Anda secara berkala untuk menjaga keamanan akun.
      </p>

      <form action="{{ route('my-profile.update.password') }}" method="POST" novalidate>
        @csrf
        @method('PATCH')

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="current_password" class="block text-sm font-medium text-gray-900">Password saat ini</label>
            <input type="password" name="current_password" id="current_password"
              placeholder="Masukkan password saat ini" value="{{ old('current_password') }}" autocomplete="off"
              class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm 
                 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500" />
            @error('current_password')
              <small class="mt-1 text-sm text-red-600">{{ $message }}</small>
            @enderror
          </div>

          <div class="sm:col-span-4">
            <label for="new_password" class="block text-sm font-medium text-gray-900">Password baru</label>
            <input type="password" name="new_password" id="new_password" placeholder="Masukkan password baru"
              value="{{ old('new_password') }}"
              class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm 
                 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500
                 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 dark:focus:ring-blue-500" />
            @error('new_password')
              <small class="mt-1 text-sm text-red-600">{{ $message }}</small>
            @enderror
          </div>

        </div>

        <div class="mt-6 flex items-center justify-start">
          <button type="submit"
            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500
               focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
            Simpan
          </button>
        </div>
      </form>
    </div>

  </div>
</x-layout>
