@extends('admin.index')

@section('content')
    <div class="container">
        <form action="" method="post" class="form-group">
            <label for="">Product Name:</label>
            <input type="text" class="form-control">
            <label for="">Price:</label>
            <input type="number" class="form-control">
            <label for="">Class:</label>
            <input type="text" class="form-control">
            <p>Note: Class must be trained to the model first</p>
            <button type="submit">Submit</button>
        </form>
    </div>
@endsection