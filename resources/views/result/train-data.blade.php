<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  @if ($data->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-left text-black dark:text-gray-300 border-b dark:border-gray-700">
        <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              No
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              content
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              label
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $index => $row)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 text-sm">
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}
              </td>
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $row->lemmatized }}
              </td>
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $row->label }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div class="mt-3">
    {{ $data->links() }}
  </div>
</x-layout>
