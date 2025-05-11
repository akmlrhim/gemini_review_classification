<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  @if ($contents->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto rounded-md">
      <table class="w-full text-left text-black dark:text-gray-300 border-b dark:border-gray-700">
        <thead class="text-sm text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-1 text-gray-800 dark:text-white">
              Content
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($contents as $row)
            <tr class="bg-white border border-gray-200">
              <td class="px-6 py-1 text-xs text-gray-900 dark:text-gray-100">
                {{ $row->content }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif


  <div class="mt-3">
    {{ $contents->links() }}
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
