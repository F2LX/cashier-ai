@extends('admin.template')

@section('content')
    <div class="container">
        <form action="/add-product/post" method="post" class="form-group" enctype="multipart/form-data">
            @csrf
            <label for="">Product Name:</label>
            <input type="text" class="form-control" name="product_name">
            <label for="">Price:</label>
            <input type="number" class="form-control" name="price">
            <label for="">Class:</label>
            <input type="text" class="form-control" name="class">
            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail:</label>
                <input class="form-control" type="file" id="formFile" name="thumbnail">
              </div>
            
            <p>Note: Class must be trained to the model first</p>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection