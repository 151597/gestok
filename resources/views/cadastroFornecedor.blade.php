@extends('home')
@section('title', 'Cadastro de Fornecedores')
@section('title2', 'Cadastro de Fornecedores')
@section('content')

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<!-- FORMULÀRIO -->
<div style="justify-content: center;">
<form id="frmFornecedor" class="form-group" action="">
  <input type="hidden" name="id" id="id">
  <div class="row">
    <div class="col-md-3">
    <label for="cadastroRazao">Raz&atilde;o Social</label>&nbsp;<span style="color: #f00;">*</span>
    <input type="text" class="form-control" name="cadastroRazao" id="cadastroRazao" placeholder="Raz&atilde;o Social" required>
    </div>
    <div class="col-md-3">
    <label for="cadastroCpf_Cnpj">CPF/CNPJ</label>&nbsp;<span style="color: #f00;">*</span>
    <input type="text" class="form-control" name="cadastroCpf_Cnpj" id="cadastroCpf_Cnpj" placeholder="CPF/CNPJ" required>
    </div>
    <div class="col-md-3">
    <label for="cadastroTelefone">Telefone:</label>&nbsp;<span style="color: #f00;">*</span>
    <input type="text" class="form-control" name="cadastroTelefone" id="cadastroTelefone" style="border: 1px solid #ced4da;" placeholder="Telefone" required>
    </div>
    <div class="col-md-3">
      <label for="cadastroResponsavel">Responsável:</label><span style="color: #f00;">*</span>
      <input type="text" class="form-control" maxlength="100" name="cadastroResponsavel" id="cadastroResponsavel" style="border: 1px solid #ced4da;" placeholder="Responsável" required>
    </div>
  </div>
  <br>
  <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
    <button type="submit" id="saveFornecedor" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
  </div>
</form><!-- FIM FORMULÀRIO -->
</div>

<table class="display" id="tableFornecedor" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th class="col">#</th>
      <th class="col">Raz&atilde;o Social</th>
      <th class="col">CNPJ</th>
      <th class="col">Telefone</th>
      <th class="col">Responsável</th>
      <th class="col" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>17.2131.2131-12</td>
      <td>EXEMPLO LTDA</td>
      <td>(41)3030-3030</td>
      <td>
            <a><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>
            <a><span style="position:relative;left:10px;cursor:pointer;" class="glyphicon glyphicon-trash"></span></a>
      </td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->

<script>

$(document).ready(function(){
  var options = {
    onKeyPress: function (cpf, ev, el, op) {
      var masks = ['000.000.000-000', '00.000.000/0000-00'];
      $('#cadastroCpf_Cnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
  }

  var telMask = {
    onKeyPress: function (tel, ev, el, op) {
      var masksTel = ['(00) 0000-00000', '(00) 00000-0000'];
      $('#cadastroTelefone').mask((tel.length > 14) ? masksTel[1] : masksTel[0], op);
    }
  }

$('#cadastroCpf_Cnpj').length > 11 ? $('#cadastroCpf_Cnpj').mask('00.000.000/0000-00', options) : $('#cadastroCpf_Cnpj').mask('000.000.000-00#', options);

$('#cadastroTelefone').length > 10 ? $('#cadastroTelefone').mask('(00) 00000-0000', telMask) : $('#cadastroTelefone').mask('(00) 0000-0000#', telMask);

});

$(window).load(function () {
    $('#tableFornecedor').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'fornecedor',
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
                    return row.id_fornecedor;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.razao_social ;
                }
            },
            {
                mRender: function (data, type, row) {
                    if(row.cpf_cnpj.length>11){
                      return row.cpf_cnpj.replace(/^(\d{2})(\d{3})?(\d{3})?(\d{4})?(\d{2})?/, "$1.$2.$3/$4-$5");
                    }else{
                      return row.cpf_cnpj.replace(/^(\d{3})(\d{3})?(\d{3})?(\d{2})?/, "$1.$2.$3-$4");;
                    }
                    
                    
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.telefone.replace(/^(\d{2})(\d)/g,"($1) $2").replace(/(\d)(\d{4})$/,"$1-$2");
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.responsavel ;
                }
            },
            {
                mRender: function (data, type, row) {

                    return '<a onClick="edicao(\''+row.id_fornecedor+'\', \''+row.razao_social+'\', \''+row.cpf_cnpj+'\', \''+row.telefone+'\', \''+row.responsavel+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;" title="EDITAR"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_fornecedor + '"></a>'
                }
            },
	 ],
         "order": [[1,"asc"]]
    });

    $('#tableFornecedor').on('click', '.table-check', function () {
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
            url: '/' + 'delete' + '/' + 'fornecedor' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableFornecedor').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });     
});

$(document).on("submit", "#frmFornecedor", function(event) {
    event.preventDefault();

    var id = $('#id').val();
    var cadastroRazao = $('#cadastroRazao').val();
    var cadastroCpf_Cnpj = $('#cadastroCpf_Cnpj').val().replaceAll(".", "").replaceAll("/", "").replaceAll("-", "");
    var cadastroTelefone = $('#cadastroTelefone').val().replaceAll(/\s/g, "").replaceAll("(", "").replaceAll(")", "").replaceAll("-", "");
    var cadastroResponsavel = $('#cadastroResponsavel').val();

      $.ajax({
        data: {
          id:id,
          cadastroRazao: cadastroRazao,
          cadastroCpf_Cnpj: cadastroCpf_Cnpj,
          cadastroTelefone: cadastroTelefone,
          cadastroResponsavel: cadastroResponsavel,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'fornecedor',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableFornecedor').DataTable().ajax.reload();
            $("#tableAction").html('Ações');
            $("#saveFornecedor").html('<span class="fa fa-floppy-o"></span>  SALVAR');
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function edicao(id, razao_social, cpf_cnpj, telefone, responsavel){
  clearFields();
 
  $('#id').val(id);
  $('#cadastroRazao').val(razao_social);
  $('#cadastroCpf_Cnpj').val(cpf_cnpj);
  if(telefone !== 'null'){
    $('#cadastroTelefone').val(telefone);
  }
  if(responsavel !== 'null'){
    $('#cadastroResponsavel').val(responsavel);
  }


  $("#saveFornecedor").html('');
  $("#saveFornecedor").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

  document.documentElement.scrollTop = 0;
  
  $('#cadastroCpf_Cnpj').unmask();
  cpf_cnpj.length > 11 ? $('#cadastroCpf_Cnpj').mask('00.000.000/0000-00') : $('#cadastroCpf_Cnpj').mask('000.000.000-00#');

  $('#cadastroCpf_Cnpj').trigger('input');
  $('#cadastroTelefone').trigger('input');
  
}

function clearFields(){
  /*$('#id').val('');
  $('#cadastroRazao').val(null);
  $('#cadastroCpf_Cnpj').val(null);
  $('#cadastroTelefone').val('');
  $('#cadastroResponsavel').val('');*/
  document.getElementById("frmFornecedor").reset();
}
</script>
@stop
