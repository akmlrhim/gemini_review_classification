<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <div class="max-w-5xl mx-auto py-4 px-6">
    <div class="bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Kelas probabilitas</h2>
      <ul class="list-disc list-inside space-y-2 text-gray-700">
        @foreach ($class_prob as $class => $prob)
          <li>
            <span class="font-medium text-blue-600">{{ $class }}</span>: {{ number_format($prob, 4) }}
          </li>
        @endforeach
      </ul>
    </div>

    <div class="space-y-3">
      <h2 class="text-xl font-semibold text-gray-800">Probabilitas kondisional (P(Kata|Kelas))</h2>
      @foreach ($cond_prob as $class => $word_probs)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
          <h3 class="text-lg font-semibold text-purple-700 mb-3">Kelas: {{ $class }}</h3>
          <div class="overflow-x-auto max-h-96 overflow-y-scroll">
            <table class="min-w-full text-md text-left text-gray-700">
              <thead class="bg-gray-200 text-gray-600 font-semibold">
                <tr>
                  <th class="px-4 py-2 border">Kata</th>
                  <th class="px-4 py-2 border">Probabilitas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($word_probs as $word => $prob)
                  <tr class="border-b">
                    <td class="px-4 py-1 border">{{ str_replace(['[', ']', "'", '"', ','], '', $word) }}</td>
                    <td class="px-4 py-1 border">{{ number_format($prob, 6) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</x-layout>
