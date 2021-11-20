@extends('home')
@section('title', 'Pedido de Compra')
@section('title2', 'Pedido de Compra')
@section('content')

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<!-- FORMULÀRIO -->

<form id="frmPedido" class="form-group" action="">
<input type="hidden" name="idusuario" id="idusuario">
  <div class="row">
    <div class="col-md-4">
        <label for="pedidoProduto">Produto/Serviço:</label><span style="color: #f00;">*</span>
        <select class="form-control" name="pedidoProduto" id="pedidoProduto" required style="width:300px;">
            <option value="">
            -- SELECIONE --
            </option>
            @if(!empty($loadProduto))
            @foreach($loadProduto as $key => $value)
                <option value="{{$value['id_produto']}}">
                    {{$value['descricao']}}
                </option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-4">
        <label for="pedidoQuantidade">Quantidade:</label><span style="color: #f00;">*</span>
        <input type="number" class="form-control" name="pedidoQuantidade" id="pedidoQuantidade" style="border: 1px solid #ced4da;width:300px;" placeholder="Quantidade">
    </div>
    <div class="col-md-3" style="top:25px;">
      <a onClick="adicionar(id)" id="adicionarPedido" class="btn btn-success"><span class="fa fa-plus"></span> Adicionar</a>
    </div>
  </div>
  <br>
  <table class="display" id="tablePedido" style="width:100%"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">Produto</th>
      <th scope="col">Quantidade</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Saco para Lixo</td>
      <td>10</td>
      <td></td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->
<br>
<div class="row">
    <div class="col-md-4">
        <label for="pedidoSolicitante">Solicitante:</label><span style="color: #f00;">*</span>
        <select class="form-control" name="pedidoSolicitante" id="pedidoSolicitante" required style="width:300px;">
            <option value="">
            -- SELECIONE --
            </option>
            @if(!empty($loadPessoas))
            @foreach($loadPessoas as $key => $value)
                <option value="{{$value['id_pessoa']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-2" style="top:25px;">
      <a onClick="savePedido(id)" id="savePedido" class="btn btn-success"><span class="fa fa-check"></span> Gerar Pedido</a>
    </div>
</div>
</form><!-- FIM FORMULÀRIO -->

<script>

$(window).load(function () {
    $('#tablePedido').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'pedido',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
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
            },
            {
                mRender: function (data, type, row) {

                    return '<a onClick="edicao(\''+row.id_usuario+'\', \''+row.id_produto+'\', \''+row.quantidade+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a><a class="table-delete" id="' + row.id + '"><span style="position:relative;left:10px;cursor:pointer;" class="glyphicon glyphicon-trash"></span></a>'
                }
            },
          ]
    });

    $('#tablePedido').on('click', '.table-delete', function () {
        var id = $(this).attr('id');
        var idproduto = $('#pedidoProduto').val();
        var quantidade = $('#pedidoQuantidade').val();
        if(confirm("Tem certeza que deseja Excluir?") === true){
          $.ajax({
            data: {
                data: id,
                idproduto: idproduto,
                quantidade: quantidade,
                "_token": "{{ csrf_token() }}"
            },
            method: "delete",
            url: '/' + 'delete' + '/' + 'pedido' + '/' + 'produto' + '/' + idusuario,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tablePedido').DataTable().ajax.reload();
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });
        
});

function adicionar(id){
    var idusuario = $('#idusuario').val();
    var pedidoFornecedor = $('#pedidoFornecedor').val();
    var pedidoSolicitante = $('#pedidoSolicitante').val();
    var pedidoProduto = $('#pedidoProduto').val();
    var pedidoQuantidade = $('#pedidoQuantidade').val();

      $.ajax({
        data: {
            id: id,
            idusuario: idusuario,
          pedidoSolicitante: pedidoSolicitante,
          pedidoProduto: pedidoProduto,
          pedidoQuantidade: pedidoQuantidade,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'pedido' + '/' + 'compra',
        success: function(result) {
            $('#tablePedido').DataTable().ajax.reload();
            $("#adicionarPedido").html('');
            $("#adicionarPedido").html('<span class="fa fa-plus"></span>  Adicionar');
            $('#idusuario').val('');
            clearFields();
        },
        error: function(result){
          console.log(result);
            if(result.responseJSON.message === 'ERROR PRODUTO'){
                alert('MESMO PRODUTO JÁ ADICIONADO!')
            }else if(result.responseJSON.message = 'SQLSTATE[23000]'){
              alert('Por Favor Preencha todos os Campos!');
            }else{
                alert("ERRO!");
            }
        }
      });
};   

function savePedido(id){
    var pedidoFornecedor = $('#pedidoFornecedor').val();
    var pedidoSolicitante = $('#pedidoSolicitante').val();
    var pedidoProduto = $('#pedidoProduto').val();
    var pedidoQuantidade = $('#pedidoQuantidade').val();

      $.ajax({
        data: {
            id: id,
          pedidoFornecedor: pedidoFornecedor,
          pedidoSolicitante: pedidoSolicitante,
          pedidoProduto: pedidoProduto,
          pedidoQuantidade: pedidoQuantidade,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'pedido' + '/' + 'compra',
        success: function(result) {
          console.log(result);
            $('#tablePedido').DataTable().ajax.reload();
            clearFields();
            alert("PEDIDO REALIZADO COM SUCESSO! N° do Pedido: "+result+"");
        },
        error: function(result){
            if(result = 'SQLSTATE[23000]'){
                alert('Por Favor Preencha todos os Campos!')
            }else{
                alert("ERRO!");
            }
        }
      });
};   

function edicao(id, id_produto, quantidade){
  clearFields();
 
  $('#idusuario').val(id);
  $('#pedidoProduto').val(id_produto);
  if(quantidade !== 'null'){
    $('#pedidoQuantidade').val(quantidade);
  }


  $("#adicionarPedido").html('');
  $("#adicionarPedido").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

  document.documentElement.scrollTop = 0;
  
}

function clearFields(){
  $('#pedidoProduto').val(null);
  $('#pedidoQuantidade').val('');
  $('#pedidoSolicitante').val(null);
}
</script>
@stop
