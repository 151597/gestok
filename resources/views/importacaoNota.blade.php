@extends('home')
@section('title', 'Importação de Nota Fiscal')
@section('title2', 'Importação de Nota Fiscal')
@section('content')

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<!-- FORMULÀRIO -->
<div >
  <form id="frmXML" class="form-group" action="" enctype='multipart/form-data'>
    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
    <input style="padding-bottom:5px;" type="file" id="xmlFile" name="xmlFile" accept="application/xml" required>
    <button type="submit" id="saveAjuste" class="btn btn-success"><span class="fa fa-upload"></span> Enviar</button>
  </form><!-- FIM FORMULÀRIO -->
</div>

<table class="display" id="tableTemp" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Produto NF</th>
      <th scope="col">Quantidade N</th>
      <th scope="col">Unidade N</th>
      <th scope="col">Produto Sist.</th>
      <th scope="col">Quantidade Sist.</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->
<br>
  <button  style="float:right;" type="button" onClick="updateTemp()" class="btn btn-success"><span class="fa fa-cogs"></span> Processar</button>

<br><br>
<!-- MODAL -->

<div class="modal fade" id="modalRelacionamento" tabindex="-1" role="dialog" aria-labelledby="modalRelacionamentoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:700px;position:relative;top:50px;">
    <div class="modal-content">
      <button type="button" onClick="clearFields()" style="padding-right:5px;" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-header">
        <h4 class="modal-title" id="modalRelacionamentoLabel"><strong>Associação de Produtos</strong></h4>
      </div>
      <div class="modal-body">
        <form id="frmRelacionamento" class="form-group" action="">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_produto_nota" name="id_produto_nota">
          <h4><strong>Origem Nota Fiscal</strong></h4>
          <div class="row">
            <div class="col-md-9">
              <label for="">Produto:</label>
              <br>
              <input class="form-control" type="text" id="produtoNota" readonly>
            </div>
            <div class="col-md-3">
              <label for="">Quantidade:</label>
              <br>
              <input class="form-control" type="text" id="qtdeNota" readonly>
            </div>
          </div>
          <br><br>
          <h4><strong>Destino no Sistema</strong></h4>
          <div class="row">
            <div class="col-md-9">
              <label for="">Produto:</label>
              <br>
              <select class="form-control" name="produtoSistema" id="produtoSistema" required>
                <option value="">
                -- SELECIONE --
                </option>
                @if(!empty($produtos))
                  @foreach($produtos as $key => $value)
                      <option value="{{$value['id_produto']}}">
                          {{$value['descricao']}}
                      </option>
                  @endforeach
                @endif
            </select>
            </div>
            <div class="col-md-3">
              <label for="">Conversão:</label>
              <br>
              <input class="form-control" id="qtdeSistema" name="qtdeSistema" type="number" required>
            </div>
          </div>
        </div>
      
        <div class="modal-footer">
          <button type="button" onClick="clearFields()" class="btn btn-secondary" data-dismiss="modal">Sair</button>
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>

$(window).load(function () {
  
  $('#tableTemp').DataTable({
    "searching": true,
    "paging": false,
    "info": false,
    "scrollX": true,
    "ajax": {
      data: { "_token": "{{ csrf_token() }}" },
      method: "get",
      url: '/' + 'load' + '/' + 'importacao' + '/' + 'temp',
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
                  return row.id_nota ;
              }
          },
          {
              mRender: function (data, type, row) {
                  return row.produto ;
              }
          },
          {
              mRender: function (data, type, row) {
                  return row.quantidade ;
              }
          },
          {
              mRender: function (data, type, row) {
                  return row.unidade_n ;
              }
          },
          {
              mRender: function (data, type, row) {
                  if(row.id_produto_sistema == null || row.id_produto_sistema == ""){
                    return '<a style="cursor:pointer;" onClick="edicao(\''+row.id_t+'\', \''+row.produto+'\', \''+row.unidade_n+'\')">Não associado</a>';
                  }else{
                    return row.descricao ;
                  }
              }
          },
          {
              mRender: function (data, type, row) {
                  return row.quantidade_sistema ;
              }
          },
          {
              mRender: function (data, type, row) {
                return '<a class="table-delete" onClick="edicao(\''+row.id_t+'\', \''+row.produto+'\', \''+row.unidade_n+'\')"><span style="position:relative;left:10px;cursor:pointer;" class="glyphicon glyphicon-edit"></span></a>'

              }
          },
          
        ]
    });
    
});

$(document).on("submit", "#frmXML", function(event) {
    event.preventDefault();

      $.ajax({
        data: new FormData(this),
        cache:false,
        contentType:false,
        processData:false,
        method: "post",
        url: '/' + 'salvar' + '/' + 'XML',
        success: function(result) {
            alert(JSON.parse(result));
            $('#xmlFile').val(null);
            $('#tableTemp').DataTable().ajax.reload();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});  

$(document).on("submit", "#frmRelacionamento", function(event) {
    event.preventDefault();

      $.ajax({
        data: new FormData(this),
        cache:false,
        contentType:false,
        processData:false,
        method: "post",
        url: '/' + 'salvar' + '/' + 'relacionamento',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            clearFields();
            $('#tableTemp').DataTable().ajax.reload();
            $('#modalRelacionamento').modal('hide');
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});

function updateTemp(){

  event.preventDefault();

    $.ajax({
      data: { "_token": "{{ csrf_token() }}"},
      method: "get",
      url: '/' + 'load' + '/' + 'update' + '/' + 'temp',
      success: function(result) {
          alert("PROCESSADO COM SUCESSO!");
          clearFields();
          $('#tableTemp').DataTable().ajax.reload();
      },
      error: function(result){
        alert("ERRO!");
      }
    });
}

    


function edicao(id, produto, nota){
  $('#id_produto_nota').val(id);
  $('#produtoNota').val(produto);
  $('#qtdeNota').val(nota);

  $('#modalRelacionamento').modal({
      backdrop:'static'
  });
}


function clearFields(){
  $('#id_produto_nota').val('');
  $('#produtoNota').val('');
  $('#qtdeNota').val('');
  $('#produtoSistema').val('');
  $('#qtdeSistema').val('');
}
</script>
@stop
