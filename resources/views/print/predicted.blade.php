<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Hasil Prediksi</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 11px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 4px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .wrap {
      word-wrap: break-word;
      max-width: 400px;
    }
  </style>
</head>

<body>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Ulasan (Lemmatized)</th>
        <th>Label Aktual</th>
        <th>Label Prediksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($predictedDetails as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="wrap">{{ is_array($item['ulasan']) ? implode(' ', $item['ulasan']) : $item['ulasan'] }}</td>
          <td>{{ $item['aktual'] }}</td>
          <td>{{ $item['prediksi'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

</body>

</html>
