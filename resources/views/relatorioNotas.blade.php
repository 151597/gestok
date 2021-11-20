@extends('home')
@section('title', 'Relatório de Notas Fiscais')
@section('title2', 'Relatório de Notas Fiscais')
@section('content')

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<div class="row">
  <div class="col-md-9">
  <form id="frmData" class="form-inline" action="">
    <label for="idFornecedor">Fornecedor:</label><span style="color: #f00;">*</span>
    <select class="form-control" onChange="$('#tableFornecedor').DataTable().ajax.reload();" name="idFornecedor" id="idFornecedor">
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($fornecedor))
        @foreach($fornecedor as $key => $value)
            <option value="{{$value['id_fornecedor']}}">
                {{$value['razao_social']}}
            </option>
        @endforeach
        @endif
    </select>
  </div>
  </form>
</div>
<br>

<table class="display" id="tableFornecedor" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">Nº NF</th>
      <th scope="col">Fornecedor</th>
      <th scope="col">Valor</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table><!-- FIM TABELA -->

<script>


$(window).load(function () {

  $('#tableFornecedor').DataTable({
      "searching": true,
      "paging": true,
      "info": false,
      "scrollX": true,
      "ajax": {
        data: { id_fornecedor:function(){ return $('#idFornecedor').val();}, 
        "_token": "{{ csrf_token() }}" },
        method: "get",
        url: '/load/relatorio/notas',
        dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": false, "targets": 3}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                  return row.idNFE.replace(/\D/g,"").replace(/(\d\d\d\d)/g, "$1 ");
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.fantasia_fornecedor ;
                }
            },
            {
              data: 'valor_total',
              render: $.fn.dataTable.render.number('.',',',2,'R$ ')
            },
            {
                mRender: function (data, type, row) {
                  return '<a href="/load/notaxml/'+row.id_nota_fiscal+'" target="_blank"><span class="fa fa-eye" style="position:relative;left:10px;cursor:pointer;"></span></a>';
                }
            },
          ]
    });

    
        
});


</script>
@stop
