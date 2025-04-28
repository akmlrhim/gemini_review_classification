<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="relative overflow-x-auto rounded-md">
    <table class="w-full text-left rtl:text-right text-black dark:text-gray-400">
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
        <tr class="text-sm bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
          <th scope="row" class="px-6 py-1 font-medium text-black whitespace-nowrap dark:text-white">
          </th>
          <td class="px-6 py-1">
          </td>
          <td class="px-6 py-1">
          </td>
          <td class="px-6 py-1">
          </td>

        </tr>
      </tbody>
    </table>
  </div>
</x-layout>
