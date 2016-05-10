<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <title> DashBoard | NosVenden </title>
    <style>
        .w-100 {
            width: 100% !important;
        }
        table th,
        table td {
            padding: 5px;
            font-family: Helvetica, Arial, sans-serif;
        }
        table thead tr th {
            background: #2196f3;
            color: #fff;
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        h3{
            margin: 0;
            font-size: 21px;
            font-family: Helvetica, Arial, sans-serif;
            font-weight: 500;
            line-height: 1.1;
            color: #222222;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-sm-6 text-right">
        <p>{{ $date }}</p>
    </div>
    <div class="col-sm-6">
        <h3>{{ $tittle }}</h3>
    </div>
</div>
<table class="w-100">
    <thead>
    <tr role="row">
        @foreach($columns as $column)
        <th>{{ $column }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
    <tr>
        @foreach($row as $value)
        <td>{{ $value }}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>