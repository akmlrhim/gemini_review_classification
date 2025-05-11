<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <div class="flex items-center justify-center gap-3 max-w-xl mx-auto p-3">
    <a href="{{ route('result.confusion-matrix-form') }}"
      class="bg-gray-700 text-sm text-white px-2 py-2 rounded-lg">Kembali</a>
  </div>

  @if (empty($conf_matrix))
    <x-empty-data></x-empty-data>
  @else
    <div class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
      <div class="mb-8">

        <h2 class="text-sm font-medium mb-2 text-gray-800">Persentase data uji: {{ session()->get('test_size') }} %
        </h2>
        <h2 class="text-sm font-medium mb-2 text-gray-800">Random seed: {{ session()->get('random_seed') }}</h2>
        <h2 class="text-md font-medium mb-2 text-green-800">Akurasi :
          <span
            class="bg-green-100 text-green-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-green-400 border border-green-400">{{ number_format($accuracy * 100, 2) }}%</span>
        </h2>
      </div>

      <div class="mb-10">
        <h2 class="text-xl font-medium mb-2 text-black">Confusion Matrix</h2>
        <div class="overflow-auto">
          <table class="table w-full text-sm border text-center">
            <thead>
              <tr class="bg-gray-200">
                <th class="border px-4 py-2">Aktual | prediksi</th>
                @foreach ($classes as $pred)
                  <th class="border px-4 py-2">{{ ucfirst($pred) }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach ($classes as $actual)
                <tr>
                  <th class="border px-4 py-2 bg-gray-100">{{ ucfirst($actual) }}</th>
                  @foreach ($classes as $pred)
                    <td class="border px-4 py-2">{{ $conf_matrix[$actual][$pred] }}</td>
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
              <h3 class="text-sm font-medium mb-2 text-black">{{ ucfirst($class) }}</h3>
              <ul class="space-y-1 text-black text-sm">
                <li>presisi: {{ $m['precision'] }} %</li>
                <li>recall {{ $m['recall'] }} %</li>
                <li>f1 score :{{ $m['f1_score'] }} %</li>
              </ul>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif


</x-layout>
