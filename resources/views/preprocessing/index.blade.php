<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <x-preprocessing-action></x-preprocessing-action>

  @if ($prepro->isEmpty())
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
              Cleaned
            </th>
            <th scope="col" class="px-6 py-2 uppercase">
              Case folding
            </th>
            <th scope="col" class="px-6 py-2 uppercase">
              Tokenize
            </th>
            <th scope="col" class="px-6 py-2 uppercase">
              Stopword
            </th>
            <th scope="col" class="px-6 py-2 uppercase">
              Lemmatized
            </th>

            <th scope="col" class="px-6 py-2 uppercase">
              Label
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($prepro as $p)
            <tr class="text-sm bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->content }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->cleaned }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->case_folding }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->tokenize }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->stopword }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->lemmatized }}
              </th>
              <th class="px-6 py-1 font-medium text-black dark:text-white">
                {{ $p->label }}
              </th>
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
