<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <div class="ml-6">
    <button type="button"
      onclick="window.location.href='{{ route('manage-user.create') }}'"
      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 uppercase">add
      user</button>
  </div>


  @if ($users->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="relative overflow-x-auto p-6">
      <table
        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg overflow-hidden">
        <thead
          class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-3 py-2 rounded-tl-lg">#</th>
            <th scope="col" class="px-3 py-2 uppercase">name</th>
            <th scope="col" class="px-3 py-2 uppercase">email</th>
            <th scope="col" class="px-3 py-2 uppercase">role</th>
            <th scope="col" class="px-3 py-2 uppercase">status</th>
            <th scope="col" class="px-3 py-2 uppercase rounded-tr-lg">action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr
              class="bg-white text-sm font-medium border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
              <td
                class="px-3 py-2 font-medium text-gray-600 whitespace-nowrap dark:text-white">
                {{ $users->firstItem() + $loop->index }}
              </td>
              <td
                class="px-3 py-2 font-medium text-gray-600 whitespace-nowrap dark:text-white">
                {{ $user->name }}
              </td>
              <td
                class="px-3 py-2 font-medium text-gray-600 whitespace-nowrap dark:text-white">
                {{ $user->email }}
              </td>
              <td
                class="px-3 py-2 font-medium text-gray-600 whitespace-nowrap dark:text-white capitalize">
                {{ $user->role }}
              </td>
              <td
                class="px-3 py-2 font-medium text-gray-600 whitespace-nowrap dark:text-white capitalize">
                {{ $user->status }}
              </td>
              <td class="px-3 py-2 whitespace-nowrap dark:text-white">
                <div class="inline-flex gap-2 items-center">
                  <a href="{{ route('manage-user.edit', $user->id) }}"
                    class="text-yellow-600 hover:underline whitespace-nowrap">Edit</a>|
                  <form action="{{ route('manage-user.destroy', $user->id) }}"
                    method="POST" onsubmit="return confirm('Apakah anda yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="text-red-600 hover:underline whitespace-nowrap bg-transparent border-none p-0 cursor-pointer">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div class="ml-6 mr-6">
    {{ $users->links() }}
  </div>


</x-layout>
