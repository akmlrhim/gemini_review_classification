<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <x-dataset-action></x-dataset-action>

  @if ($dataset->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-left rtl:text-right text-black dark:text-gray-400 border-b dark:border-gray-700">
        <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-2">
              #
            </th>
            <th scope="col" class="px-6 py-2">
              reviewId
            </th>
            <th scope="col" class="px-6 py-2">
              userName
            </th>
            <th scope="col" class="px-6 py-2">
              userImage
            </th>
            <th scope="col" class="px-6 py-2">
              content
            </th>
            <th scope="col" class="px-6 py-2">
              score
            </th>
            <th scope="col" class="px-6 py-2">
              thumbsUpCount
            </th>
            <th scope="col" class="px-6 py-2">
              reviewCreatedVersion
            </th>
            <th scope="col" class="px-6 py-2">
              at
            </th>
            <th scope="col" class="px-6 py-2">
              replyContent
            </th>
            <th scope="col" class="px-6 py-2">
              repliedAt
            </th>
            <th scope="col" class="px-6 py-2">
              appVersion
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dataset as $row)
            <tr class="text-xs bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-1 font-medium text-black whitespace-nowrap dark:text-white">
                {{ $dataset->firstItem() + $loop->index }}
              </th>
              <td class="px-6 py-1">
                {{ $row->reviewId }}
              </td>
              <td class="px-6 py-1">
                {{ $row->userName }}
              </td>
              <td class="px-6 py-1">
                <img src="{{ $row->userImage }}" alt="{{ $row->userName }}" class="w-10">
              </td>
              <td class="px-6 py-1">
                {{ $row->content }}
              </td>
              <td class="px-6 py-1">
                {{ $row->score }}
              </td>
              <td class="px-6 py-1">
                {{ $row->thumbsUpCount }}
              </td>
              <td class="px-6 py-1">
                {{ $row->reviewCreatedVersion }}
              </td>
              <td class="px-6 py-1">
                {{ $row->at }}
              </td>
              <td class="px-6 py-1">
                {{ $row->replyContent }}
              </td>
              <td class="px-6 py-1">
                {{ $row->repliedAt }}
              </td>
              <td class="px-6 py-1">
                {{ $row->appVersion }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif


  <div class="mb-3 mt-3">
    {{ $dataset->links() }}
  </div>

</x-layout>
