<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>

  @if (Auth::user()->role == 'admin')
    <x-alert></x-alert>
    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-md font-medium text-gray-700 mb-2 text-center">Jumlah data latih / train</h2>
        <p class="text-xl font-bold text-blue-600">{{ $trainData }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-md font-medium text-gray-700 mb-2 text-center">Jumlah Data uji / test</h2>
        <p class="text-xl font-bold text-red-600">{{ $testData }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex flex-col justify-center items-center">
        <h2 class="text-md font-medium text-gray-700 mb-2 text-center">Jumlah data</h2>
        <p class="text-xl font-bold text-red-600">{{ $totalData }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 md:col-span-3">
        <h2 class="text-md font-medium text-gray-700 mb-4 text-center">Perbandingan jumlah label</h2>
        <div class="relative overflow-x-auto rounded-md">
          <canvas id="labelChart" class="w-[300px] h-[300px] mx-auto"></canvas>
        </div>
      </div>
    </div>
  @endif


  {{-- dashboard user  --}}
  @if (Auth::user()->role == 'user')
    <div class="max-w-6xl mx-auto py-10 bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-200">
      <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Panduan penggunaan aplikasi</h5>
      <ul class="space-y-1 text-sm text-gray-700 list-disc list-inside dark:text-gray-400">
        <li>
          Masuk ke link <a class="text-blue-600 hover:underline dark:text-blue-500"
            href="https://colab.research.google.com/drive/1DalW5QcK8ITkN99Lgm3nrwrZOaHN9j8F?usp=sharing"
            target="_blank">Google Colab</a> untuk melakukan pengambilan dan pembersihan data.
        </li>
        <li>
          Jalankan semua sel di notebook dari atas ke bawah untuk melakukan proses <strong>data cleaning</strong>,
          seperti:
          <ul class="list-disc list-inside ml-4">
            <li>Menghapus tanda baca dan karakter khusus</li>
            <li>Mengubah semua teks menjadi huruf kecil</li>
            <li>Melakukan tokenisasi</li>
            <li>Menghapus stopword</li>
            <li>Melakukan lemmatization</li>
          </ul>
        </li>
        <li>
          Setelah proses selesai, unduh file output yang telah dibersihkan (biasanya dalam format CSV).
        </li>
        <li>
          Kembali ke aplikasi sistem analisis sentimen, lalu masuk ke menu <strong>Preprocessing >
            Preprocessing</strong>.
        </li>
        <li>
          Gunakan tombol <em>Import Data</em> untuk mengunggah file hasil cleaning dari Google Colab.
        </li>
        <li>
          Data yang telah dibersihkan kini siap digunakan untuk proses analisis.
        </li>
      </ul>

    </div>
  @endif

  @push('script')
    <script type="module">
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
    </script>
  @endpush

</x-layout>
