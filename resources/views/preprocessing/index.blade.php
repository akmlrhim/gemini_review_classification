<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <x-preprocessing-action></x-preprocessing-action>

  @if ($prepro->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-left text-black dark:text-gray-300 border-b dark:border-gray-700">
        <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              case folding
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              tokenize
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              stopword
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              lemmatized
            </th>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white uppercase">
              label
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($prepro as $row)
            <tr class="bg-white border border-gray-200">
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $row->case_folding }}
              </td>
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $row->tokenize }}
              </td>
              <td class="px-6 py-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $row->stopword }}
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
    {{ $prepro->links() }}
  </div>

</x-layout>
