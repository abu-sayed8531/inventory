@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Sales Report</h4>
                        <label class="form-label mt-2">Date From</label>
                        <input id="FromDate" type="date" class="form-control"/>
                        <label class="form-label mt-2">Date To</label>
                        <input id="ToDate" type="date" class="form-control"/>
                        <button onclick="SalesReport()" class="btn mt-3 bg-gradient-primary">Download</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Stock Report</h4>
                        <label class="form-label mt-2">Date From</label>
                        <input id="PFromDate" type="date" class="form-control"/>
                        <label class="form-label mt-2">Date To</label>
                        <input id="PToDate" type="date" class="form-control"/>
                        <button onclick="StockReport()" class="btn mt-3 bg-gradient-primary">Download</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>Customer Report</h4>
                        <label class="form-label mt-2">Date From</label>
                        <input id="CFromDate" type="date" class="form-control"/>
                        <label class="form-label mt-2">Date To</label>
                        <input id="CToDate" type="date" class="form-control"/>
                        <button onclick="CustomerReport()" class="btn mt-3 bg-gradient-primary">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function SalesReport(){
        let FromDate = document.getElementById('FromDate').value;
        let ToDate = document.getElementById('ToDate').value;
        if(FromDate.length === 0 || ToDate.length === 0){
            errorToast('Date range required');
        }
        else{
            window.open('/sales-report/'+FromDate+'/'+ToDate);
        }
    }
    function StockReport(){
        let FromDate = document.getElementById('PFromDate').value;
        let ToDate = document.getElementById('PToDate').value;
        if(FromDate.length === 0 || ToDate.length === 0){
            errorToast('Date range required');
        }
        else{
            window.open('/stock-report/'+FromDate+'/'+ToDate);
        }
    }
    function CustomerReport(){
        let FromDate = document.getElementById('CFromDate').value;
        let ToDate = document.getElementById('CToDate').value;
        if(FromDate.length === 0 || ToDate.length === 0){
            errorToast('Date range required');
        }
        else{
            window.open('/customer-report/'+FromDate+'/'+ToDate);
        }
    }
</script>