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

<a href="{{ route('sales.report', ['FromDate' => $from_date, 'ToDate' => $to_date, 'download' => 1]) }}" class="btn btn-primary">
    Download Report
</a>

<h3>Summary</h3>

<table class="customers" >
    <thead>
    <tr>
        <th>Report</th>
        <th>Date</th>
        <th>Total</th>
   
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Customer Report</td>
        <td>{{$from_date}} to {{$to_date}}</td>
        <td>{{$total}}</td>
        
    
    </tr>
    </tbody>
</table>


<h3>Details</h3>
<table class="customers" >
    <thead>
    <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Email</th>
       
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
   @foreach($customer as $item)
   <tr>
    <td>{{$item->name}}</td>
    <td>{{$item->mobile}}</td>
        <td>{{$item->email}}</td>
    
    <td>{{date('Y-m-d',strtotime($item->created_at))}}</td>

    
   </tr>
   @endforeach

    </tbody>
</table>
</body>
</html>




