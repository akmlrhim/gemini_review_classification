<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <div class="max-w-6xl mx-auto py-4 bg-white shadow-md rounded-lg p-6 mb-4 border border-gray-200">

    <div class="mt-2">
      <div class="overflow-auto rounded border-gray-300">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-100 text-xs uppercase">
            <tr>
              <th class="px-2 py-1">#</th>
              <th class="px-2 py-1">Ulasan</th>
              <th class="px-2 py-1">Label Aktual</th>
              <th class="px-2 py-1">Label Prediksi</th>
            </tr>
          </thead>
          <tbody class="text-sm">
            @foreach ($paginatedPredictions as $index => $data)
              <tr>
                <td class="px-2 py-1">{{ $index + 1 }}</td>
                <td class="px-2 py-1">{{ $data['ulasan'] }}</td>
                <td class="px-2 py-1">{{ $data['aktual'] }}</td>
                <td class="px-2 py-1">{{ $data['prediksi'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-4">
      {{ $paginatedPredictions->links() }}
    </div>
  </div>
</x-layout>
