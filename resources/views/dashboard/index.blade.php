<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  @if (Auth::user()->role == 'admin')
    <x-alert></x-alert>

    {{-- @if ($title->isEmpty())
    <x-empty-data></x-empty-data>
  @else --}}
    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-md font-medium text-gray-700 mb-2 text-center">Jumlah Data</h2>
        {{-- <p class="text-xl font-bold text-blue-600">{{ $jumlahDataset }}</p> --}}

        <form action="{{ route('dashboard.reset') }}" method="POST" class="mt-2"
          onsubmit="return confirm('Apakah anda yakin?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="px-2 py-1 bg-red-500 text-sm text-white rounded hover:bg-red-600 transition">
            Reset Data
          </button>
        </form>
      </div>

      {{-- <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-md font-medium text-gray-700 mb-2 text-center">Jumlah Data setelah dibersihkan</h2>
        <p class="text-xl font-bold text-red-600">{{ $cleanedData }}</p>
      </div> --}}

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 md:col-span-2">
        <h2 class="text-md font-medium text-gray-700 mb-4 text-center">Perbandingan jumlah label</h2>
        <div class="relative overflow-x-auto rounded-md">
          <canvas id="labelChart" class="w-[300px] h-[300px] mx-auto"></canvas>
        </div>
      </div>

    </div>
    {{-- @endif --}}
  @endif


  @if (Auth::user()->role == 'user')
    <x-dashboard-user></x-dashboard-user>
  @endif

  @push('script')
    {{-- <script type="module">
      const ctx = document.getElementById('labelChart').getContext('2d');
      const chart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: {!! json_encode($label->pluck('label')) !!},
          datasets: [{
            label: 'Jumlah Data per Label',
            data: {!! json_encode($label->pluck('total')) !!},
            backgroundColor: ['#4CAF50', '#FFC107', '#F44336'],
            borderColor: '#333',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              precision: 0
            }
          }
        }
      });
    </script> --}}
  @endpush

</x-layout>
