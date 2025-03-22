<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Category</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-secondary"/>
            <div class="table-responsive">
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">
                 <tr>
                    <td>12</td>
                    <td>new category</td>
                    <td><div data-bs-target="#update-modal" data-bs-toggle="modal" class="btn btn-success mx-1">Edit</div><div class="btn btn-danger mx-1" data-bs-target="#delete-modal" data-bs-toggle="modal">Delete</div></td>
                 </tr>  
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>

