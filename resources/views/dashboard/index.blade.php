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
    <div class="max-w-6xl mx-auto rounded-lg p-6 mb-2">

      <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-1">
          <button type="button"
            class="flex items-center justify-between w-full p-2 font-medium rtl:text-right text-gray-800 bg-white rounded-md shadow-md hover:bg-gray-50"
            data-accordion-target="#accordion-open-body-1" aria-expanded="false" aria-controls="accordion-open-body-1">
            <span class="flex items-center"><svg class="w-5 h-5 me-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                  clip-rule="evenodd"></path>
              </svg> Bagaimana cara menggunakan aplikasi ?</span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5 5 1 1 5" />
            </svg>
          </button>
        </h2>
        <div id="accordion-open-body-1" class="hidden" aria-labelledby="accordion-open-heading-1">
          <div class="p-5 border border-b-0 bg-white border-gray-200 dark:border-gray-700 dark:bg-gray-900">
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
                Setelah proses selesai, unduh file output yang telah dibersihkan (format CSV).
              </li>
              <li>
                Kembali ke aplikasi sistem analisis sentimen, lalu masuk ke menu <strong>Data > Data
                  Preprocessing</strong>.
              </li>
              <li>
                Gunakan tombol import data untuk mengunggah file hasil cleaning dari Google Colab.
              </li>
              <li>
                Lakukan split data untuk membagi data menjadi data latih (train) dan data uji (test) dengan memasukkan
                persentase data train yang diinginkan misal 80 %, maka data uji (test) akan menjadi sisanya yaitu
                20 % .
              </li>
              <li>
                Hasil perhitungan Naive Bayes dan Pengujian Performa dengan Confusion Matrix akan dapat langsung
                dilihat.
              </li>
            </ul>
          </div>
        </div>


      </div>
    </div>

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
        <p class="text-xl font-bold italic text-black">{{ $totalData }}</p>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 md:col-span-3">
        <h2 class="text-md font-medium text-gray-700 mb-4 text-center">Perbandingan jumlah label</h2>
        <div class="relative overflow-x-auto rounded-md">
          <canvas id="labelChart" class="w-[300px] h-[300px] mx-auto"></canvas>
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
