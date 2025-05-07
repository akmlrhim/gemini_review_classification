<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="max-w-2xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Masukkan Parameter untuk Perhitungan</h2>

    <form action="{{ route('result.confusion-matrix-process') }}" method="POST">
      @csrf

      <div class="mb-4">
        <label for="test_size" class="block text-sm font-medium text-gray-700">Test Size (%)</label>
        <input type="number" id="test_size" name="test_size" value="{{ old('test_size') }}"
          class="mt-1 p-2 w-full border rounded-md" min="0" max="100">
        @error('test_size')
          <small class="text-red-500">{{ $message }}</small>
        @enderror
      </div>

      <div class="mb-4">
        <label for="random_seed" class="block text-sm font-medium text-gray-700">Random Seed</label>
        <input type="number" id="random_seed" name="random_seed" value="{{ old('random_seed') }}"
          class="mt-1 p-2 w-full border rounded-md">
        @error('random_seed')
          <small class="text-red-500">{{ $message }}</small>
        @enderror

      </div>

      <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Tampilkan Hasil</button>
    </form>
  </div>
</x-layout>
