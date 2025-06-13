<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  @if (empty($confMatrix))
    <x-empty-data></x-empty-data>
  @else
    <div class="max-w-6xl mx-auto py-4 bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">

      <div class="mb-3">
        <a href="{{ route('preprocessing.index') }}" class="text-blue-500 hover:underline text-sm">Ke
          Preprocessing?</a>
      </div>

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
</x-layout>
