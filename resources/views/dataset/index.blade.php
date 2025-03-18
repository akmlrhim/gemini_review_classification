<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="mb-4">
    <a href="{{ route('dataset.import') }}"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import
      data
    </a>

    <form action="{{ route('dataset.delete-all') }}" method="POST" class="inline" onsubmit="confirmDelete(event)">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Delete
        all data</button>
    </form>

  </div>

  <x-alert></x-alert>

  <div class="relative overflow-x-auto rounded-md border border-gray-200">
    <table class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            No.
          </th>
          <th scope="col" class="px-6 py-3">
            Review / Content
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($dataset as $row)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              {{ $dataset->firstItem() + $loop->index }}
            </th>
            <td class="px-6 py-4">
              {{ $row->review }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

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
