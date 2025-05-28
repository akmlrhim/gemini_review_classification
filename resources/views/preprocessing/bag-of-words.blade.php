<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <div class="overflow-x-auto">
    @foreach ($data as $doc)
      <div class="mb-3 shadow rounded-lg shadow p-4 bg-white">
        <h2 class="text-md font-semibold mb-2">Dokumen ID: {{ $doc->id }}</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-1 py-1 text-left font-medium text-gray-700">Kata</th>
                <th class="px-1 py-1 text-left font-medium text-gray-700">Frekuensi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach ($tf[$doc->id] as $word => $count)
                <tr>
                  <td class="px-1 py-1 text-gray-800">{{ str_replace(['[', ']', "'", '"', ','], '', $word) }}</td>
                  <td class="px-1 py-1 text-gray-800">{{ $count }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach

    <div class="mt-2">
      {{ $data->links() }}
    </div>
  </div>
</x-layout>
