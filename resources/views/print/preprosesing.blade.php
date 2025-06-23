<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>{{ $title }}</title>
  <style>
    body {
      font-family: sans-serif;
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
      font-size: 11px;
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
  <table>
    <thead>
      <tr>
        <th>Lemmatized</th>
        <th>Label</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($preprosesing as $item)
        <tr>
          <td>{{ $item->lemmatized }}</td>
          <td>{{ $item->label }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
