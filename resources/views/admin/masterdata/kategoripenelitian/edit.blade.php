@extends('adminlayout.layout')
@section('content')
<div class="container-fluid">
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-times"></i> 
        {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check"></i> {{Session::get('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div style="margin-left: 10px;" class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Edit Data Kategori Penelitian</h1>
    </div>
    <form method="POST" enctype="multipart/form-data" action="/admin/masterdata/kategoripenelitian/update/{{$datakp->id_kategori_penelitian}}">
    @csrf
    <div class="card shadow">
        <div class="form-group card-header shadow">
            <div class="row">
                <div class="col">
                    <h3 class="font-weight-bold text-primary"><i class="fas fa-university"></i> Data Kategori Penelitian</h3>
                </div>
            </div>
        </div>
        <div class="form-group card-body">    
            <label for="kategori_penelitian" class="font-weight-bold text-dark">Nama Kategori Penelitian</label>
            <textarea class="form-control" id="kategori_penelitian" name="kategori_penelitian" placeholder="Masukan Nama Kategori Penelitian">{{$datakp->kategori_penelitian}}</textarea>
            <small style="color: red">
                @error('kategori_penelitian')
                    {{$message}}
                @enderror
            </small>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengupdate Data?')"><i class="fas fa-save"></i> Update</button>
                    <a href="{{route('masterdata-kategoripenelitian-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection