<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <pre>{{ json_encode($tf_idf, JSON_PRETTY_PRINT) }}</pre>
</x-layout>
