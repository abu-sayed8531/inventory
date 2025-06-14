<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Product</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0  bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Unit</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<script>
    getList();
    
        async function getList(){
        showLoader();
        let res = await axios.post("/product-list");
        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        tableData.DataTable().destroy();
        tableList.empty();

        res.data.data.forEach(function (item,index){
            let row = `<tr>
                            <td>${index+1}</td>
                            <td>${item['name']}</td>
                            <td>${item['price']}</td>
                            <td>${item['unit']}</td>
                             <td><img class="w-50 h-auto" alt="" src="storage/${item['img_url']}"></td>

                            <td>
                                <button data-path="${item['img_url']}" data-id="${item['id']}" class="btn editBtn btn-sm btn-outline-success">Edit</button>
                                <button data-path="${item['img_url']}" data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                            </td>
                        </tr>`
            tableList.append(row)
        })

        $('.editBtn').on('click',async function(){
            let id = $(this).data('id');
            let filePath = $(this).data('path');
            await FillUpUpdateForm(id,filePath);
            $("#update-modal").modal('show');

        })

        $('.deleteBtn').on('click',async function(){
            let id = $(this).data('id');
            let path = $(this).data('path');
            $("#delete-modal").modal('show');
            $("#deleteID").val(id);
            $("#deleteFilePath").val(path);
        })

        new DataTable('#tableData',{
            order:[[0,'asc']],
            lengthMenu:[5,10,15,20,100]
        });
    }

    
</script>