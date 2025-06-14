<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>

                                <select type="text" class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPrice">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit">
                                <br/>
                                <img class='w-15' src="{{asset('images/default.jpg')}}" id="newImg"/>
                                <br/>
                                <label class="form-label">Image</label>
                                <input class="form-control" id ="productImg" type="file" oninput="newImg.src=window.URL.createObjectURL(this.files[0])">

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>

<script>
    FillCategory();
    async function FillCategory(){
        let res = await axios.post('/category-list');
        res.data.data.forEach(function(item){
        let option = `<option value="${item['id']}">${item['name']}</option>`; 
        $('#productCategory').append(option);
        });
    }

    async function Save(){
        let pCategory = document.getElementById('productCategory').value;
        let pName = document.getElementById('productName').value;
        let pPrice = document.getElementById('productPrice').value;
        let pUnit = document.getElementById('productUnit').value;
        let pImage = document.getElementById('productImg').files[0];
        if(pCategory.length ===0){
            errorToast('Category required');
        }
        else if (pName.length === 0 ){
            errorToast('Name is required');
        }
        else if(pPrice.length === 0){
            errorToast('Price is required');
        }
        else if(pUnit.length === 0){
            errorToast('Unit is required');
        }
        else if(!pImage){
            errorToast('Image is required');
        }
        else{
            document.getElementById('modal-close').click();
            let data = new FormData();
            data.append('name',pName);
            data.append('price', pPrice);
            data.append('qty',pUnit);
            data.append('image',pImage),
            data.append('category_id',pCategory);
            let config = {
                  headers : {
                    'content-type' : 'multipart/form-data'
                  }
            };
            showLoader();
            let res = await axios.post('product-create',data,config);
            hideLoader();
            console.log(res);
            if(res.status === 201 && res.data.status === "success"){
                  successToast('Success ......');
                  document.getElementById('save-form').reset();
                  await getList();
                
            }
            else{
                errorToast('Failed ...');
            }
            
        }
    }
</script>