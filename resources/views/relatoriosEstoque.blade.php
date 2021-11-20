@extends('home')
@section('title', 'Relatório de Estoque')
@section('title2', 'Relatório de Estoque')
@section('content')


<table class="display" id="tableRelatorioEstoque" style="width:100%;"> 
  <thead>
    <tr>
      <th scope="col">Produto</th>
      <th scope="col">Quantidade</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script>

$(window).load(function () {

    $('#tableRelatorioEstoque').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
        data: { "_token": "{{ csrf_token() }}" },
        method: "get",
        url: '/load/relatorios/estoque',
        dataType: "json"
    },
    "language":{
        url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
    },
        "columnDefs":[
                { "orderable": false, "targets": 1}
                
            ],
        "columns": [
            {
                mRender: function (data, type, row) {
                    return row.descricao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.quantidade ;
                }
            }
            ]
    });

        
});



</script>

@stop
