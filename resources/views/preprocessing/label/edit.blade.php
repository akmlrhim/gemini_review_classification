<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <div class="max-w-2xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <form action="{{ route('preprocessing.label.update', $label->id) }}" method="POST">
      @method('PUT')
      @csrf

      <div class="mb-4">
        <label for="label" class="block text-md font-medium text-gray-700 mb-3">"{{ $label->case_folding }}"</label>
        <select id="label" name="label"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
          <option value="" disabled {{ $label->label == '' ? 'selected' : '' }}>pilih kelas atau label</option>
          <option value="positif" {{ $label->label == 'positif' ? 'selected' : '' }}>positif</option>
          <option value="negatif" {{ $label->label == 'negatif' ? 'selected' : '' }}>negatif</option>
        </select>
        @error('label')
          <small class="text-red-500">{{ $message }}</small>
        @enderror
      </div>
      <div>
        <button type="button" onclick="window.location.href='{{ route('preprocessing.label') }}'"
          class="bg-gray-500 text-white px-2 py-1 text-sm rounded-md">kembali</button>
        <button type="submit" class="bg-blue-500 text-sm text-white px-2 py-1 rounded-md">simpan</button>
      </div>
    </form>
  </div>
</x-layout>
