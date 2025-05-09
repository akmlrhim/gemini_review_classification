<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  @if ($label->isEmpty())
    <x-empty-data></x-empty-data>
  @else
    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-xl font-semibold text-gray-700 mb-2 text-center">Jumlah Data</h2>
        <p class="text-4xl font-bold text-blue-600">{{ $jumlahDataset }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-xl font-semibold text-gray-700 mb-2 text-center">Jumlah Data setelah dibersihkan</h2>
        <p class="text-4xl font-bold text-red-600">{{ $cleanedData }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 md:col-span-2">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 text-center">Perbandingan jumlah label</h2>
        <div class="relative overflow-x-auto rounded-md">
          <canvas id="labelChart" class="w-full h-[120px]"></canvas>
        </div>
      </div>

    </div>
  @endif


  @push('script')
    <script type="module">
      const ctx = document.getElementById('labelChart').getContext('2d');
      const chart = new Chart(ctx, {
        type: 'bar',
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
    </script>
  @endpush

</x-layout>
