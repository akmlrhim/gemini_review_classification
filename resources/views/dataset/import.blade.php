<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="flex items-center justify-center">
    <div class="lg:w-1/2 sm:w-full md:w-full">
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="small_size">
          Masukkan file dataset (.csv)
        </label>
        <form action="{{ route('dataset.import.process') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-5">
            <input
              class="block w-full text-xs text-gray-90x0 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
              id="small_size" type="file" name="file">
            @error('file')
              <small class="text-red-700 text-sm">{{ $message }}</small>
            @enderror
          </div>
          <div>
            <a href="{{ route('dataset.index') }}"
              class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-300 me-2 text-sm">
              Kembali</a>

            <button type="submit"
              class="px-5 py-2 bg-blue-600 font-medium text-white rounded-lg hover:bg-blue-700 transition duration-300 text-sm">
              Upload File</button>

          </div>
        </form>
      </div>
    </div>
  </div>
</x-layout>
