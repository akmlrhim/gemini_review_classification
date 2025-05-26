<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <x-alert></x-alert>

  @if ($users->isEmpty())
    <x-empty-data></x-empty-data>
  @endif

  <div class="relative overflow-x-auto rounded-md p-8">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            #
          </th>
          <th scope="col" class="px-6 py-3 uppercase">
            name
          </th>
          <th scope="col" class="px-6 py-3 uppercase">
            email
          </th>
          <th scope="col" class="px-6 py-3 uppercase">
            status
          </th>
          <th scope="col" class="px-6 py-3 uppercase">
            action
          </th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-white dark:bg-gray-800">
          @foreach ($users as $user)
            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white">
              {{ $users->firstItem() + $loop->index }}
            </td>
            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white">
              {{ $user->name }}
            </td>
            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white">
              {{ $user->email }}
            </td>
            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white">
              {{ $user->status }}
            </td>
            <td class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-white inline-flex gap-2">
              <a href="{{ route('manage-user.edit', $user->id) }}"
                class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-500 dark:hover:text-yellow-700">Edit</a>

              <form action="{{ route('manage-user.destroy', $user->id) }}" method="POST"
                onsubmit="return confirm('Apakah anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-700">Delete</button>
              </form>

            </td>
          @endforeach
        </tr>
      </tbody>
    </table>
  </div>


</x-layout>
