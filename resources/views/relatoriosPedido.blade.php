@extends('home')
@section('title', 'Relatório de Pedido')
@section('title2', 'Relatório de Pedido')
@section('content')


<table class="display" id="tableRelatorioEstoque" style="width:100%;"> 
  <thead>
    <tr>
      <th scope="col" class="col-sm-1">Nº do Pedido</th>
      <th scope="col" class="col-sm-3">Solicitante</th>
      <th scope="col" class="col-sm-2">Data</th>
      <th scope="col" class="col-sm-2">Situação</th>
      <th scope="col" class="col-sm-1">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<div class="modal fade" id="modalProdutos" tabindex="-1" role="dialog" aria-labelledby="modalProdutosLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="position:relative;top:0px;max-height:600px;overflow-y:scroll;scroll-behavior:smooth;scrollbar-width: thin;;width:700px;">
    <div class="modal-content">
      <button type="button" onClick="clearFields()" style="padding-right:5px;" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-header">
        <h4 class="modal-title" id="modalProdutosLabel"><strong>Produtos</strong></h4>
      </div>
      <div class="modal-body">

          <table class="table" id="tableProdutos"> 
            <thead>
                <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Quantidade</th>
                </tr>
            </thead>
            <tbody id="produtos">
            </tbody>
        </table>
        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
        </div>
    </div>
  </div>
</div>

<script>

$(window).load(function () {

    $('#tableRelatorioEstoque').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "order": [ [3, 'asc'], [ 0, "desc" ]],
        "ajax": {
        data: { "_token": "{{ csrf_token() }}" },
        method: "get",
        url: '/load/relatorios/pedido',
        dataType: "json"
    },
    "language":{
        url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
    },
        "columnDefs":[
                { "orderable": false, "targets": 4}
                
            ],
        "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_pedido ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.data ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.status ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return '<a><span class="fa fa-eye" onClick="exibeProdutos(\''+row.id_pedido+'\')" style="position:relative;left:10px;cursor:pointer;"></span></a>';
                }
            },
            ]
    });

        
});

function exibeProdutos(id){

    $("#produtos").empty();

    $.ajax({
        method: "get",
        url: '/load/relatorios/pedido/'+id,
        success: function(result) {
            var produto = JSON.parse(result);

           for(var j=0;j<produto.length;j++){
                $("#produtos").append(
                '<tr><td>'+produto[j].descricao+'</td><td>'+produto[j].quantidade+'</td></tr>'
                );
            }
        },
        error: function(result){
        alert("ERRO!");
        }
    });

    $('#modalProdutos').show().scrollTop(0);
    $('#modalProdutos').modal({
        backdrop:'static'
    });
  
}



</script>

@stop
