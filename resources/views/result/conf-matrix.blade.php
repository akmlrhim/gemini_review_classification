<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <div class="flex items-center justify-center gap-3 max-w-xl mx-auto p-3">
    <a href="{{ route('result.confusion-matrix-form') }}" class="bg-gray-700 text-white px-2 py-2 rounded-lg">Kembali</a>
  </div>

  @if (empty($conf_matrix))
    <x-empty-data></x-empty-data>
  @else
    <div class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
      <div class="mb-8">

        <h2 class="text-md font-semibold mb-2 text-gray-800">Persentase data tes: {{ session()->get('test_size') }} %
        </h2>
        <h2 class="text-md font-semibold mb-2 text-gray-800">Random seed: {{ session()->get('random_seed') }}</h2>
        <h2 class="text-xl font-semibold mb-2 text-green-800">Akurasi: {{ number_format($accuracy * 100, 2) }}%</h2>
      </div>

      <div class="mb-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Confusion Matrix</h2>
        <table class="table-auto w-full text-md border text-center">
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

      <div>
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Metrik Per Kelas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          @foreach ($metrics as $class => $m)
            <div class="bg-white rounded-lg p-4 border border-gray-900">
              <h3 class="text-lg font-bold mb-2 text-black">{{ ucfirst($class) }}</h3>
              <ul class="space-y-1 text-gray-700">
                <li><strong>Precision:</strong> {{ $m['precision'] }} %</li>
                <li><strong>Recall:</strong> {{ $m['recall'] }} %</li>
                <li><strong>F1-Score:</strong> {{ $m['f1_score'] }} %</li>
              </ul>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif


</x-layout>
