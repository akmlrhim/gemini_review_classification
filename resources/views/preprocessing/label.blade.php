<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  @if ($isLabel->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-left rtl:text-right text-black dark:text-gray-400">
        <thead class="text-sm text-white bg-gray-900 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-2 uppercase">
              Content
            </th>
            <th scope="col" class="px-6 py-2 uppercase">
              Label
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($isLabel as $rows)
            <tr class="text-sm bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $rows->content }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $rows->label }}
              </th>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div class="mt-3">
    {{ $isLabel->links() }}
  </div>

</x-layout>
