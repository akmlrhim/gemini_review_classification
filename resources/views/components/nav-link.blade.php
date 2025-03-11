@props(['active' => false])

<a {{ $attributes }}
  class="flex items-center p-2 mb-1 {{ $active ? 'bg-primary-800 text-ghost-white' : 'text-primary-800' }} rounded-lg dark:text-white hover:bg-primary-800 hover:text-ghost-white dark:hover:bg-gray-700 group"
  aria-current="{{ $active ? 'page' : false }}">
  {{ $slot }}
</a>
