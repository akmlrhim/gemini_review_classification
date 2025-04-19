<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="mb-4">
    <a href="{{ route('dataset.import') }}"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import
      data</a>

    <form action="{{ route('dataset.delete-all') }}" method="POST" class="inline" onsubmit="confirmDelete(event)">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Delete
        all data</button>
    </form>

  </div>

  <x-alert></x-alert>

  @if ($dataset->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
              thumbUpCount
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
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <th scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
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
                {{ $row->thumbUpCount }}
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


  <div class="mt-3">
    {{ $dataset->links() }}
  </div>


  @push('script')
    <script>
      function confirmDelete(event) {
        event.preventDefault();

        if (confirm("Apakah Anda yakin ingin menghapus semua data?")) {
          event.target.submit();
        }
      }
    </script>
  @endpush

</x-layout>
