<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  <div class="overflow-x-auto">
    <pre class="p-4 text-sm bg-gray-100 rounded-md">
			{{ json_encode($tf_idf, JSON_PRETTY_PRINT) }}
		</pre>
  </div>
</x-layout>
