<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="max-w-6xl mx-auto py-4 px-6">
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
      <h2 class="text-md font-semibold text-gray-800 mb-4 uppercase">Hasil Naive Bayes</h2>
      <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm">
        @foreach ($class_prob as $class => $prob)
          <li>
            <span class="font-medium">Probabilitas kelas {{ $class }}:</span>
            {{ number_format($prob * 100, 2) . '%' }}
          </li>
          <li class="ml-6 text-gray-600">
            Total kata dalam kelas {{ $class }}:
            {{ $word_counts_by_class[$class] }}
          </li>
        @endforeach
        <li class="font-medium">Jumlah kata unik : {{ $vocab_size }}</li>
      </ul>
    </div>

    <div class="space-y-6">
      @foreach ($cond_prob as $class => $word_probs)
        <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
          <h3 class="text-md font-semibold text-black mb-3">Kelas : {{ $class }}</h3>
          <div class="overflow-x-auto max-h-96 overflow-y-scroll text-sm">
            <table class="min-w-full text-center border-b text-gray-700">
              <thead class="bg-gray-200 text-gray-600 font-semibold sticky top-0">
                <tr>
                  <th class="px-4 py-2 ">Kata</th>
                  <th class="px-4 py-2 ">Frekuensi</th>
                  <th class="px-4 py-2 ">Probabilitas <i>likelihood</i></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($word_probs as $word => $prob)
                  <tr class="-b">
                    <td class="px-4 py-1 ">{{ str_replace(['[', ']', "'", '"', ','], '', $word) }}</td>
                    <td class="px-4 py-1  text-center">
                      {{ $raw_counts[$class][$word] ?? 0 }}
                    </td>
                    <td class="px-4 py-1  text-center">
                      {{ number_format($prob, 6) }}
                    </td>
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
