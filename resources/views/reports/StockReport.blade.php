<html>
<head>
    <style>
        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 12px !important;
        }

        .customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .customers tr:nth-child(even){background-color: #f2f2f2;}

        .customers tr:hover {background-color: #ddd;}

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            padding-left: 6px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>

<a href="{{ route('stock.report', ['PFromDate' => $from_date, 'PToDate' => $to_date, 'download' => 1]) }}" class="btn btn-primary">
    Download Report
</a>

<h3>Summary</h3>

<table class="customers" >
    <thead>
    <tr>
        <th>Report</th>
        <th>Date</th>
         <th>Total product</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Stock Report</td>
        <td>{{$from_date}} to {{$to_date}}</td>
        <td>{{$total}}</td>
       
    
    </tr>
    </tbody>
</table>


<h3>Details</h3>
<table class="customers" >
    <thead>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Date</th>
        
    </tr>
    </thead>
    <tbody>
   @foreach($product as $item)
   <tr>
    <td>{{$item->name}}</td>
    <td>{{$item->price}}</td>
    <td>{{$item->unit}}</td>
    <td>{{date('Y-m-d',strtotime($item->created_at))}}</td>

    
   </tr>
   @endforeach

    </tbody>
</table>
</body>
</html>




