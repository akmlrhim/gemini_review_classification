<div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-xl mx-auto p-3">
  <div class="w-full sm:w-auto">
    <form action="{{ route('preprocessing.delete.all') }}" method="POST" onsubmit="return confirm('Apakah anda yakin?');">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="w-full sm:w-auto px-4 py-2 text-sm font-bold text-red-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 uppercase">
        Delete All
      </button>
    </form>
  </div>

  <div class="w-full sm:w-auto">
    <button type="button" data-modal-target="split-modal" data-modal-toggle="split-modal"
      class="w-full sm:w-auto px-4 py-2 text-sm font-bold text-red-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 uppercase">
      Split Data
    </button>
  </div>

  <div class="w-full sm:w-auto">
    <button type="button" data-modal-target="import-modal" data-modal-toggle="import-modal"
      class="w-full sm:w-auto px-4 py-2 text-sm font-bold text-green-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 uppercase">
      Import
    </button>
  </div>

  <div class="w-full sm:w-auto">
    <button type="button" onclick="window.location.href='{{ route('preprocessing.index') }}'"
      class="w-full sm:w-auto px-4 py-2 text-sm font-bold text-blue-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 uppercase">
      Back
    </button>
  </div>
</div>

{{-- modal split  --}}
<div id="split-modal" tabindex="-1" aria-hidden="true"
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-md">
  <div class="relative p-4 w-full max-w-md max-h-full">
    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          Split Data
        </h3>
        <button type="button"
          class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="split-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <div class="p-4 md:p-5">
        <form class="space-y-4" action="{{ route('preprocessing.split-data') }}" method="POST">
          @csrf
          <div>
            <select name="train_data" id="train_data"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
              <option value="">Pilih persentase train data</option>
              @foreach ([60, 70, 80, 90] as $percent)
                <option value="{{ $percent }}" {{ old('train_data') == $percent ? 'selected' : '' }}>
                  {{ $percent }}%
                </option>
              @endforeach
            </select>
            @error('train_data')
              <small class="text-red-500 text-sm"> {{ $message }}</small>
            @enderror
          </div>
          <button type="submit"
            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Proses</button>
        </form>
      </div>
    </div>
  </div>
</div>
{{-- end split modal --}}


{{-- import modal  --}}
<div id="import-modal" tabindex="-1" aria-hidden="true"
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-md">
  <div class="relative p-4 w-full max-w-xl max-h-full">
    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
          Import file hasil preprocessing (csv)
        </h3>
        <button type="button"
          class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="import-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <div class="p-4 md:p-5">
        <form class="space-y-4" action="{{ route('preprocessing.import') }}" enctype="multipart/form-data"
          id="importForm" method="POST">
          @csrf
          <div>
            <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File
              (csv)</label>
            <input type="file" name="file" id="file" value="{{ old('file') }}"
              class="w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
            @error('file')
              <small class="text-red-500 text-sm"> {{ $message }}</small>
            @enderror
          </div>

          <button type="submit" id="importBtn"
            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Import</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- javascript  --}}
<script>
  document.getElementById('importForm').addEventListener('submit', function() {
    const btn = document.getElementById('importBtn');
    btn.innerText = 'Loading...';
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
  });
</script>
