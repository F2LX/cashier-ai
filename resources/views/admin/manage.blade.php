@extends('admin.template')


@section('content')
<div class="card-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10px">ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th style="width: 40px">Label/Class</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr class="align-middle">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>
                        {{ $item->price }}
                    </td>
                    <td>{{ $item->class }}</td>
                    <td>
                        <a href="/delete/{{ $item->id }}" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> <!-- /.card-body -->
@endsection