<div class="flex items-center justify-center gap-3 max-w-xl mx-auto p-3">
  <div class="inline-flex rounded-md shadow-xs" role="group">
    <form action="{{ route('preprocessing.delete.all') }}" method="POST" onsubmit="return confirm('Apakah anda yakin?');">
      @csrf
      @method('DELETE')
      <button type="submit"
        class="px-4 py-2 text-xs font-medium text-red-500 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 uppercase">
        delete all
      </button>
    </form>

    <form action="{{ route('preprocessing.split-data') }}" method="POST"
      onsubmit="return confirm('Yakin ingin membagi data training dan testing?');">
      @csrf
      <button type="submit"
        class="px-4 py-2 text-xs font-medium text-green-700 bg-white border border-gray-200 rounded hover:bg-gray-100 uppercase">
        SPLIT DATA
      </button>
    </form>

    <button type="button" onclick="window.location.href='{{ route('import.index') }}'"
      class="px-4 py-2 text-xs font-medium text-green-700 bg-white border border-gray-200 rounded hover:bg-gray-100 uppercase">
      IMPORT
    </button>

    <button type="button" onclick="window.location.href='{{ route('preprocessing.index') }}'"
      class="px-4 py-2 text-xs font-medium text-blue-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 uppercase">
      back
    </button>
  </div>
</div>
