<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  <x-alert></x-alert>

  <div class="max-w-5xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
    <form action="{{ route('result.predict') }}" class="space-y-4" method="POST">
      @csrf

      <label for="sentence" class="w-full md:w-40 text-sm font-medium text-gray-900 capitalize">sentence</label>
      <div class="flex-1">
        <textarea id="sentence" rows="4" name="sentence"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
          placeholder="write your sentence here for predict the label ....">{{ old('sentence') }}</textarea>
        @error('sentence')
          <small class="text-red-500">{{ $message }}</small>
        @enderror
      </div>

      <button type="submit" class="bg-blue-500 text-sm text-white px-3 py-1 rounded-md uppercase">predict</button>
    </form>


    {{-- hasil predik  --}}
    @if (session('prediction'))
      <div class="mt-8 p-4 bg-gray-100 rounded border border-gray-300">
        <h2 class="text-lg font-semibold mb-2 text-gray-800">Prediction Result</h2>
        <p><strong>Sentence : </strong> {{ session('input') }}</p>
        <p><strong>Predicted Label :</strong> <span class="text-blue-600">{{ session('prediction') }}</span></p>
      </div>
    @endif
  </div>
</x-layout>
