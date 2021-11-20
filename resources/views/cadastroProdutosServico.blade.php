@extends('home')
@section('title', 'Cadastro de Produtos')
@section('title2', 'Produtos/Serviços')
@section('content')
<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>
<!-- FORMULÀRIO -->
<div style="justify-content: center;">
  <form id="frmProduto" class="form-group" action="">

    <input type="hidden" name="id" id="id">

    <div class="row">
      <div class="col-md-6">
        <label for="cadastroDescricao">Descri&ccedil;&atilde;o:</label>&nbsp;<span style="color: #f00;">*</span>
        <input type="text" class="form-control" onkeyup="codigoSKU()" name="cadastroDescricao" id="cadastroDescricao" style="border: 1px solid #ced4da;" placeholder="Descrição" required>
      </div>

      <div class="col-md-6">
        <label for="cadastroFornecedor">Fornecedor:</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" onChange="codigoSKU()" name="cadastroFornecedor" id="cadastroFornecedor" required>
            <option value="">
            -- SELECIONE --
            </option>
            
              @foreach($loadFornecedores as $key => $value)
                  <option value="{{$value['id_fornecedor']}}">
                      {{$value['razao_social']}}
                  </option>
              @endforeach
          
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3">
        <label for="cadastroEAN">Código EAN:</label>
        <input type="text" class="form-control" name="cadastroEAN" id="cadastroEAN" style="border: 1px solid #ced4da;" placeholder="Código EAN">
      </div>
      <div class="col-md-3">
        <label for="cadastroSKU">SKU:</label>&nbsp;<span style="color: #f00;">*</span>
        <input type="text" class="form-control" name="cadastroSKU" id="cadastroSKU" style="border: 1px solid #ced4da;" placeholder="SKU" required>
      </div>
      <div class="col-md-3">
        <label for="cadastroTipo">Tipo:</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" name="cadastroTipo" id="cadastroTipo" required>
          <option value="">
          -- SELECIONE --
          </option>
          <option value="P">Produto</option>
          <option value="S">Serviço</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="cadastroValor">Valor:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="input-group m-b-sm">
          <span class="input-group-addon" id="basic-addon1">R$</span>
          <input aria-describedby="basic-addon1" maxlength="13" type="text" class="form-control" name="cadastroValor" id="cadastroValor" style="border: 1px solid #ced4da;" required>
        </div>
      </div>
    </div>
    <br>
    <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
      <button type="submit" id="saveProduto" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
    </div>
  </form><!-- FIM FORMULÀRIO -->
</div>

<table class="display" id="tableProduto" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col-sm-2">#</th>
      <th scope="col-sm-2">Descrição</th>
      <th scope="col-sm-2">Fornecedor</th>   
      <th scope="col-sm-2">Cód. EAN</th>
      <th scope="col-sm-2">SKU</th>
      <th scope="col-sm-2">Tipo</th>
      <th scope="col-sm-2">Valor</th>
      <th scope="col-sm-2" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table><!-- FIM TABELA -->

<script>

function codigoSKU(){
  var desstr = $("#cadastroDescricao").val();
  var desmatches = desstr.match(/\b(\w)/g); 
  var desacronym = desmatches.join('');

  var sku = desacronym;

  if($("#cadastroFornecedor").val() != ''){
    var fornstr = $("#cadastroFornecedor").find('option:selected').text();
    var fornmatches = fornstr.match(/\b(\w)/g); 
    var fornacronym = fornmatches.join('');

    sku = desacronym+fornacronym;
  }

  $("#cadastroSKU").val(sku.toUpperCase());
}

$(window).load(function () {

    $("#cadastroValor").mask("#.##0,00",{reverse:true});

    $('#tableProduto').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'produto',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": false, "targets": 7,
                  "width": "17%", "targets": 7}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_produto ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.descricao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.razao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.ean ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.sku ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.tipo ;
                }
            },
            {
              data: 'valor',
              render: $.fn.dataTable.render.number('.',',',2,'R$ ')
              /*mRender: function (data, type, row) {
                            return row.valor ;
              }*/
            },
            {
                mRender: function (data, type, row) {

                    return '<a onClick="edicao(\''+row.id_produto+'\', \''+row.descricao+'\', \''+row.id_fornecedor+'\', \''+row.ean+'\', \''+row.sku+'\', \''+row.charTipo+'\', \''+row.valor+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;" title="EDITAR"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_produto + '"></a>'
                }
            },
          ],
          "order":[[1,"asc"]]
    });

    $('#tableProduto').on('click', '.table-check', function () {
      var check = [];
          $("input[name='check']").each(function() {
            if ($(this).prop("checked")) {
                check.push($(this).prop("id"));
            }
        });
        console.log(check);
        if(check.length > 0){
          $("#tableAction").html('<a class="table-delete btn btn-danger" style="position:relative; right:10px;"><span style="cursor:pointer;" class="glyphicon glyphicon-trash" title="DELETAR"></span>&nbsp;&nbsp;Remover Ítens</a>');
        }else{
          $("#tableAction").html('Ações');
        }       
    });

    $('#tableAction').on('click', '.table-delete', function () {
        var check = [];
            $("input[name='check']").each(function() {
              if ($(this).prop("checked")) {
                  check.push($(this).prop("id"));
              }
          });

        if(confirm("Tem certeza que deseja Excluir?") === true){
          $.ajax({
            data: {
                data: check,
                "_token": "{{ csrf_token() }}"
            },
            method: "delete",
            url: '/' + 'delete' + '/' + 'produto' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableProduto').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });
});

$(document).on("submit", "#frmProduto", function(event) {
    event.preventDefault();

    var id = $('#id').val();
    var cadastroDescricao = $('#cadastroDescricao').val();
    var cadastroFornecedor = $('#cadastroFornecedor').val();
    var cadastroEAN = $('#cadastroEAN').val();
    var cadastroSKU = $('#cadastroSKU').val();
    var cadastroTipo = $('#cadastroTipo').val();
    var cadastroValor = $('#cadastroValor').val();
    console.log(cadastroValor.replaceAll(".","").replaceAll(",","."));
      $.ajax({
        data: {
          id:id,
          cadastroDescricao: cadastroDescricao,
          cadastroFornecedor: cadastroFornecedor,
          cadastroEAN: cadastroEAN,
          cadastroSKU: cadastroSKU,
          cadastroTipo: cadastroTipo,
          cadastroValor:cadastroValor.replaceAll(".","").replaceAll(",","."),
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'produto',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableProduto').DataTable().ajax.reload();
            $("#saveProduto").html('<span class="fa fa-floppy-o"></span>  SALVAR');
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function edicao(id, descricao, fornecedor, ean, sku, tipo, valor){
  clearFields();
 
  $('#id').val(id);
  $('#cadastroDescricao').val(descricao);
  $('#cadastroFornecedor').val(fornecedor);
  if(ean !== 'null'){
    $('#cadastroEAN').val(ean);
  }
  $('#cadastroSKU').val(sku);
  $('#cadastroTipo').val(tipo);
  $('#cadastroValor').val(valor);
  

  $("#saveProduto").html('');
  $("#saveProduto").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

  document.documentElement.scrollTop = 0;
  
}

function clearFields(){
  $('#id').val('');
  $('#cadastroDescricao').val('');
  $('#cadastroFornecedor').val(null);
  $('#cadastroEAN').val('');
  $('#cadastroSKU').val('');
  $('#cadastroTipo').val(null);
  $('#cadastroValor').val('');
}
</script>
@stop
