<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6 mb-2 border border-gray-200">
    <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
      @csrf

      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Upload file (csv)</label>
      <input
        class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
        id="file" name="file" type="file" />
      @error('file')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
      @enderror

      <div class="mt-3">
        <button type="button" onclick="window.location.href='{{ route('preprocessing.index') }}'"
          class="bg-gray-500 text-sm text-white px-3 py-1 rounded-md uppercase">Back</button>
        <button type="submit" class="bg-blue-500 text-sm text-white px-3 py-1 rounded-md uppercase"
          id="importBtn">import</button>
      </div>
    </form>
  </div>

  @push('script')
    <script>
      document.getElementById('importForm').addEventListener('submit', function() {
        const btn = document.getElementById('importBtn');
        btn.innerText = 'Loading...';
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
      });
    </script>
  @endpush


</x-layout>
