<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  @if (empty($confMatrix))
    <x-empty-data></x-empty-data>
  @else
    <div class="max-w-6xl mx-auto py-4 bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">
      <h2 class="text-md font-medium mb-6 text-green-800">Akurasi :
        <span
          class="bg-green-100 text-green-800 text-lg font-bold me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-green-400 border border-green-400">{{ number_format($accuracy * 100, 2) }}%</span>
      </h2>

      <div class="mb-8">
        <h2 class="text-xl font-medium mb-2 text-black">Matrix</h2>
        <div class="overflow-auto">
          <table class="table w-full text-md border text-center">
            <thead>
              <tr class="bg-gray-200">
                <th class="border px-3 py-1">Aktual | prediksi</th>
                @foreach ($classes as $pred)
                  <th class="border px-3 py-1">{{ $pred }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach ($classes as $actual)
                <tr>
                  <th class="border px-3 py-1 bg-gray-100">{{ $actual }}</th>
                  @foreach ($classes as $pred)
                    <td class="border px-3 py-1">{{ $confMatrix[$actual][$pred] }}</td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div>
        <h2 class="text-xl font-medium mb-2 text-black">Metrik Per Kelas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          @foreach ($metrics as $class => $m)
            <div class="bg-white rounded-lg p-4 border border-gray-900">
              <h3 class="text-sm font-extrabold mb-2 text-black">{{ $class }}</h3>
              <ul class="space-y-1 capitalize text-black text-sm">
                <li>presisi : {{ $m['precision'] }} %</li>
                <li>recall : {{ $m['recall'] }} %</li>
                <li>f1 score : {{ $m['f1_score'] }} %</li>
              </ul>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif


  {{-- modal split  --}}
  <div id="split-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-md">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <div
          class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            Split Data
          </h3>
          <button type="button"
            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="split-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
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
</x-layout>
