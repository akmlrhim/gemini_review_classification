<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>{{ $title }}</title>
  <style>
    body {
      margin-top: 2cm;
      font-family: 'Times New Roman', Times, serif;
      font-size: 12px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      word-wrap: break-word;
    }

    th,
    td {
      border: 0.5px solid black;
      padding: 1px;
      text-align: left;
      vertical-align: top;
      font-size: 6px;
    }

    th {
      background-color: #f2f2f2;
    }

    h2 {
      text-align: left;
      margin-bottom: 2px;
    }
  </style>
</head>

<body>
  <h2>{{ $title }}</h2>

  <table>
    <thead>
      <tr>
        <th>Lemmatized</th>
        <th>Label</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($test as $index => $item)
        <tr>
          <td>{{ $item->lemmatized }}</td>
          <td>{{ $item->label == 'positif' ? 'p' : ($item->label == 'negatif' ? 'n' : $item->label) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
