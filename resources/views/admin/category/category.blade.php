@extends('layouts.db')

@section('title')
    Kumo ~ Category List
@endsection

@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-8">
                @if (session('trashed'))
                    <div class="alert alert-danger">{{ session('trashed') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>Category List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Serial</th>
                                <th>Category</th>
                                <th>Added By</th>
                                <th>Image</th>
                                <th>Icon</th></th>
                                <th>Action</th>
                            </tr>
                            @foreach ($categories as $serial => $category)
                            <tr>
                                <td>{{ $serial+1 }}</td>
                                <td>{{ $category->category_name }}</td>
                                <td>
                                    @if (App\Models\User::where('id', $category->added_by)->exists())
                                        {{ $category->rel_to_user->name }}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
                                    <img width="50" src="{{ asset('uploads/category/'.$category->category_img) }}" alt="">
                                </td>
                                <td style="font-family: fontawesome;" ><i class="{{ $category->icon }}"></i></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" style="color: #36C95F" href="{{ route('category.edit',$category->id) }}">Edit</a>
                                            <a class="dropdown-item text-danger" href="{{ route('category.dlt', $category->id) }}">Move to Trash</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Add Category</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                @php
                                    $icons = array (
                                        'fa fa-television',
                                        'fa fa-life-ring',
                                        'fa fa-headphones',
                                        'fa fa-gift',
                                        'fa fa-bath',
                                        'fa fa-shopping-bag',
                                        );
                                @endphp
                                <label for="" class="form-label">Select Icon</label>
                                <div>
                                    @foreach ($icons as $icon)
                                        <i style="font-family: 'fontawesome'; font-size: 25px; margin-right: 10px;" class="fa {{ $icon }}" data-icon="{{ $icon }}"></i>
                                    @endforeach
                                </div>
                                <input type="text" id="icon" class="form-control mt-3" name="icon" placeholder="Icon">
                            </div>
                            <div class="mb-3">
                                {{-- <input type="hidden" class="form-control" name="category_id"> --}}
                                <label for="" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category_name" placeholder="category name">
                                @error('category_name')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Category Image</label>
                                <input type="file" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" name="category_img">
                                <img class="mt-2" width="100" src="" id="blah" alt="">
                                @error('category_img')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if (session('deleted'))
                    <div class="alert alert-warning">{{ session('deleted') }}</div>
                @endif
                @if (session('restored'))
                    <div class="alert alert-info">{{ session('restored') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>Trashed List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Serial</th>
                                <th>Category</th>
                                <th>Added By</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($trash_categories as $serial => $trash_category)
                            <tr>
                                <td>{{ $serial+1 }}</td>
                                <td>{{ $trash_category->category_name }}</td>
                                <td>
                                @if (App\Models\User::where('id', $category->added_by)->exists());
                                    {{ $trash_category->rel_to_user->name }}
                                @else
                                    Unknown
                                @endif
                                </td>
                                <td>
                                    <img width="50" src="{{ asset('uploads/category/'.$trash_category->category_img) }}" alt="">
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" style="color: #36C95F" href="{{ route('category.restore', $trash_category->id) }}">Restore</a>
                                            <a class="dropdown-item text-danger" href="{{ route('dlt.trashed', $trash_category->id) }}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer_script')
    <script>
        $('.fa').click(function(){
            var icon = $(this).attr('data-icon');
            $('#icon').attr('value', icon);
        });
    </script>
@endsection
