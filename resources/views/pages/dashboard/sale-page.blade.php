@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-sm " id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                       <div class="col-12">
                           <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                           <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                           <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                           <span class="text-xxs">Discount(%):</span>
                           <input onkeydown="return false" value="0" min="0"  type="number" step="0.25" onchange="DiscountChange()" class="form-control w-40 " id="discountP"/>
                           <p>
                              <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                           </p>
                       </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>




    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" id="PId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" id="PName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input  type='number' min="1"class="form-control" id="PQty">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success" >Add</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (async ()=>{
          showLoader();
          await CustomerList();
          await ProductList();
          hideLoader();
        })()

        async function CustomerList(){
            showLoader();
            let res = await axios.post('/customer-list');
            hideLoader();
            let customerTable = $('#customerTable');
            let customerList = $('#customerList');
            customerTable.DataTable().destroy();
            customerList.empty();
            res.data.data.forEach(function(item, index){
                let row = `
                          <tr class="text-sm">
                          <td><i class="bi bi-person"></i>${item['name']}</td>
                          <td><a data-name="${item['name']}" data-email="${item['email']}" data-id = "${item['id']}" class="btn btn-sm btn-outline-dark addCustomer px-2 py-1 m-0 txt-xxs">Add</a></td>
                          </tr>
                          `;
                customerList.append(row);          
            });
            
            $('.addCustomer').on('click',function(){
                let name = $(this).data('name');
                let email = $(this).data('email');
                let id = $(this).data('id');
                $('#CName').text(name);
                $('#CEmail').text(email);
                $('#CId').text(id);
            });

            new DataTable('#customerTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }
        async function ProductList(){
            showLoader();
            let res = await axios.post('/product-list');
            hideLoader();
            let productTable = $('#productTable');
            let productList = $('#productList');
            productTable.DataTable().destroy();
            productList.empty();
            res.data.data.forEach(function(item,index){
                let row = `
                          <tr class="text-sm">
                          <td><i class="bi bi-person"></i>${item['name']}</td>
                          <td><a data-name="${item['name']}" data-price="${item['price']}" data-id = "${item['id']}" data-qty="${item['unit']}" class="btn btn-sm btn-outline-dark addProduct px-2 py-1 m-0 txt-xxs">Add</a></td>
                          </tr>
                          `;
                productList.append(row);          
            });
            $('.addProduct').on('click',function(){
               let name = $(this).data('name');
               let price = $(this).data('price');
               let id = $(this).data('id');
               let qty = $(this).data('qty');
               addModal(id,name,price,qty);
            });
            new DataTable('#productTable',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }
        function addModal(id,name,price,qty){
            $('#PId').val(id);
            $('#PName').val(name);
            $('#PPrice').val(price);
            document.getElementById('PQty').setAttribute('max',qty);
            document.getElementById('PQty').setAttribute('placeholder','Available:'+' '+qty);

            $('#create-modal').modal('show');
        }
         
        let invoiceItemList = [];
        function add(){
            let PId = $('#PId').val();
            let PName = $("#PName").val();
            let PPrice = $('#PPrice').val();
            let PQty = $('#PQty').val();
            if(PId.length === 0 || PName.length === 0 || PPrice.length=== 0 || PQty.length === 0){
                errorToast('All field required');
            }
            else{

                let productTotal = (parseFloat(PPrice)*PQty).toFixed(2);
                let items = {product_id : PId, product_name: PName,qty:PQty,sale_price:productTotal};
                invoiceItemList.push(items);
    
                $('#create-modal').modal('hide');
                ShowInvoiceItem();
            }
        } 
            function ShowInvoiceItem(){
                //let invoiceList = document.getElementById('invoiceList');
                let invoiceList = $('#invoiceList');
                    invoiceList.empty();
                invoiceItemList.forEach(function(item,index){
                    let row = `<tr class="text-xs">
                                <td>${item['product_name']}</td>
                                <td>${item['qty']}</td>
                                <td>${item['sale_price']}</td>
                                <td><a data-index="${index}" class = "btn btn-sm remove text-xxs px-2 py-1 m-0">remove</a></td>
                                </tr>
                              `;
                    invoiceList.append(row);          
                });

                $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            });

                
                CalculateGrandTotal();
            }
            function removeItem(index){
                  invoiceItemList.splice(index,1);
                  ShowInvoiceItem();
            }
            function CalculateGrandTotal(){
                let totalEle = document.getElementById('total');
                let vatEle = document.getElementById('vat');
                let discountEle = document.getElementById('discount');
                let payableEle = document.getElementById('payable');
                let discountP = document.getElementById('discountP').value;
                let discountPercent = parseFloat(discountP);
                let total = 0;
                let vat = 0;
                let discount = 0;
                let payable = 0;
                invoiceItemList.forEach(function(item,index){
                    total +=  (parseFloat(item['sale_price']));
                });
                 console.log(discountPercent);
                if(discountPercent !==0){
                    
                    discount = parseFloat((total*discountPercent)/100);
                    total = total - discount;
                    vat = parseFloat((total*5)/100);
                    payable =  total + vat;
                }
                else{
                      vat = parseFloat((total*5)/100);
                      payable =  total + vat;
                }
                totalEle.innerText= total.toFixed(2);
                discountEle.innerText = discount.toFixed(2);
                payableEle.innerText = payable.toFixed(2);
                vatEle.innerText = vat.toFixed(2);
            }
            function DiscountChange(){
                CalculateGrandTotal();
            }

            async function createInvoice(){
            let total=document.getElementById('total').innerText;
            let discount=document.getElementById('discount').innerText
            let vat=document.getElementById('vat').innerText
            let payable=document.getElementById('payable').innerText
            let CId=document.getElementById('CId').innerText;
            if(total.length ===0|| discount.length === 0||vat.length ===0 || payable.length === 0|| CId.length ===0){
                errorToast('All field required');
            }
            else if(invoiceItemList.length === 0){
                errorToast('Product info required');
            }
            else{
                let data = {
                        total:total,
                        discount: discount,
                        vat : vat, 
                        payable : payable,
                        customer_id : CId,
                        products : invoiceItemList,     
            };
            showLoader();
            let res = await axios.post('invoice-create',data);
            hideLoader();
            if(res.status === 200 && res.data.status === 'success'){
                successToast('Success.');
                window.location.href = '/invoice-page';
            }
            else{
                errorToast('Failed...');
            }
            }
            

            }
    </script>
@endsection
