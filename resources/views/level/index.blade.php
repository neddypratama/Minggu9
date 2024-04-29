@extends('layout.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a> 
        </div> 
      </div> 
      <div class="card-body"> 
        @if (session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_level"> 
          <thead> 
            <tr>
              <th>ID</th>
              <th>Nama Kode</th>
              <th>Nama Level</th>
              <th>Aksi</th>
            </tr> 
          </thead> 
      </table> 
    </div> 
  </div> 
@endsection 
 
@push('css') 
@endpush 
@push('js') 
  <script> 
    $(document).ready(function() { 
      var dataLevel = $('#table_level').DataTable({ 
          pageLength: 25,
          processing: true,
          serverSide: true,     // serverSide: true, jika ingin menggunakan server side processing 
          dom: '<"html5buttons">Bfrtip',
        language: {
            buttons: {
                colvis : 'show / hide', // label button show / hide colvisRestore: "Reset Kolom" //lael untuk reset kolom ke default
            }
        },
        buttons : [
            {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] },
            {
                extend:'csv' ,
                title:'Tabel User',
                // exportOptions : columns: [0,1,2,3,4]
            },
            {
                extend: 'pdf', 
                title:'Tabel User'
                // exportOptions : columns: [0,1,2,3,4]
            },
            {
                extend: 'excel', 
                title: 'Tabel User'
                // exportOptions : columns: [0,1,2,3,4]
            },
            {
                extend:'print',
                title: 'Tabel User'
                // exportOptions : columns: [0,1,2,3,4]
            },
        ],
          ajax: { 
              "url": "{{ url('level/list') }}", 
              "dataType": "json", 
              "type": "POST",
              "data":function(d){
                d.level_id = $('#level_id').val();
              }
          }, 
          columns: [ 
            { 
             data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()            
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
              data: "level_kode",                
              className: "", 
              orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
            },{ 
              data: "level_nama",                
              className: "", 
              orderable: true,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: true    // searchable: true, jika ingin kolom ini bisa dicari 
            },{ 
              data: "aksi",                
              className: "", 
              orderable: false,    // orderable: true, jika ingin kolom ini bisa diurutkan 
              searchable: false    // searchable: true, jika ingin kolom ini bisa dicari 
            } 
          ] 
      }); 
      $('#level_id').on('change', function(){
        dataLevel.ajax.reload();
      });
    }); 
  </script> 
@endpush