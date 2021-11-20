@extends('home')
@section('title', 'Orçamentos')
@section('title2', 'Orçamentos')
@section('content')

<?php $arrayFornecedor = []; ?>

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js') }}"></script>

<script src="//cdn.datatables.net/plug-ins/1.10.21/api/sum().js"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>

<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet" type="text/css"/>

<!-- FORMULÀRIO -->

<form id="frmPedido" class="form-group" action="">
  <div class="row">
    <div class="col-md-5">
        <label for="orcamentoFornecedor">Fornecedor</label><span style="color: #f00;">*</span>
        <select class="form-control" name="orcamentoFornecedor" style="width:100%;" id="orcamentoFornecedor" required multiple="multiple"> 
            @if(!empty($loadOrcamento))
            @foreach($loadOrcamento as $key => $value)
              <?php array_push($arrayFornecedor, $value['id_fornecedor']);?>
                <option value="{{$value['id_fornecedor']}}">
                    {{$value['razao_social']}}
                </option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-7" style="top:25px;"> 
      <a id="adicionarOrcamento" class="btn btn-success"><span class="fa fa-plus"></span> Adicionar</a>
    </div>
  </div>
</form><!-- FIM FORMULÀRIO -->
<br>
<table class="display" id="tableOrcamento" style="width:100%"> <!-- TABELA -->
  <thead>
    <tr>
      <th id="0" scope="col">Produto</th>
      <th id="1" scope="col">Quantidade</th>
      <th id="forn2">Fornecedor</th>
      <th id="forn3">Fornecedor</th>
      <th id="forn4">Fornecedor</th>
      <th id="forn5">Fornecedor</th>
    </tr>
  </thead>
  <tbody>
    <tr>
    </tr>
  </tbody>
  <tfoot>
      <tr>
          <th>Subtotal</th>
          <th></th>
          <th id="footerResult1">R$</th>
          <th id="footerResult2">R$</th>
          <th id="footerResult3">R$</th>
          <th id="footerResult4">R$</th>
      </tr>
  </tfoot>
</table><!-- FIM TABELA -->

<form id="frmOrcamentoPagamento" class="form-group" action="">
<!-- MODAL PAGAMENTO-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informações de Pagamento</h5>
        <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
              <label for="orcamentoForma">Forma:
              <select class="form-control" name="orcamentoForma" id="orcamentoForma" required> 
                  <option value="">
                  -- SELECIONE --
                  </option>
                      <option value="B">
                          Boleto
                      </option>
                      <option value="D">
                          Depósito
                      </option>
              </select>
          </div>
          <div class="col-md-4">
              <label for="orcamentoParcela">Parcela:
                <input class="form-control" type="number" min="0" value="0" id="orcamentoParcela" name="orcamentoParcela" required>
          </div>
          <div class="col-md-4">
              <label for="orcamentoPrimeira">Primeira Parcela em:
              <input class="form-control" type="text" id="orcamentoPrimeira" name="orcamentoPrimeira" required>
          </div>
        </div>
        <br><br>
        <h5>Dados bancários</h5>
        <div style="padding:10px;border:3px solid black;border-radius:1%;">
          <div class="row">
            <div class="col-md-6">
                <label for="orcamentoBanco">Banco:
                  <select class="form-control" name="orcamentoBanco" id="orcamentoBanco" disabled required> 
                      <option value="">
                      -- SELECIONE --
                      </option>
                      @foreach($bancos as $banco)
                      
                          <option value="<?=$banco['value']?>">
                              <?=$banco['value']." - ".$banco['label']?>
                          </option>
                      @endforeach
                  </select>
            </div>  
            <div class="col-md-6">
                <label for="orcamentoAgencia">Agência:
                  <input class="form-control" type="text" id="orcamentoAgencia" name="orcamentoAgencia" style="width:250px;" disabled required>
            </div>      
        </div>
        <div class="row">
              <div class="col-md-4">
                <label for="orcamentoConta">Conta:
                  <input class="form-control" type="text" id="orcamentoConta" name="orcamentoConta" disabled required>
              </div>
              <div class="col-md-4">
                <label for="orcamentoTipoConta">Tipo Conta:
                  <select class="form-control" name="orcamentoConta" id="orcamentoTipoConta" disabled required> 
                    <option value="">
                    -- SELECIONE --
                    </option>
                        <option value="C">
                            Conta Corrente
                        </option>
                        <option value="P">
                            Conta Poupança
                        </option>
                  </select>
              </div>
              <div class="col-md-4">
                <label for="orcamentoOp">Op:
                  <input class="form-control" type="text" id="orcamentoOp" name="orcamentoOp" disabled required>
              </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="savePagamento">Adicionar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="frmNPedido" class="form-group" action="">
<!-- MODAL PEDIDO -->
<div class="modal fade" id="exampleModalPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Selecione o N° de Pedido</h5>
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>-->
      </div>
      <div class="modal-body">
        <table class="table" id="tableNPedido"> <!-- TABELA -->
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Pedido</th>
              <th scope="col">Solicitante</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row"></th>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table><!-- FIM TABELA -->
      </div>
      <div class="modal-footer">
        <a class="btn btn-success" onClick="selecionePedido()" id="selecionePedido">Salvar e Fechar</a>
      </div>
    </div>
  </div>
</div>
</form>
<div style="height: 100%;display: flex;justify-content: center;align-items: center;">
  <a onClick="saveOrcamento()" id="saveOrcamento" class="btn btn-success"><span class="fa fa-check"></span> Salvar Orçamentos</a>
</div>
<script>
var radio = 0;
var somaTotalColumn2 = [];
var somaTotalColumn3 = [];
var somaTotalColumn4 = [];
var somaTotalColumn5 = [];
var produto = [];
var quantidade = [];
var arrFornecedor = <?=json_encode($arrayFornecedor)?>;
var countS2 = 0;
var idOrcamento = null;
//var arrColuna = [3,2];

$(document).ready(function(){
  $("#saveOrcamento").addClass('disabled');
  //$("#adicionarOrcamento").addClass('disabled');
  
    $("#orcamentoForma").click(function(){
      var forma = $("#orcamentoForma option:selected").val();
      if(forma == 'B' || forma == ''){
        document.getElementById("orcamentoBanco").disabled = true;
        document.getElementById("orcamentoAgencia").disabled = true;
        document.getElementById("orcamentoTipoConta").disabled = true;
        document.getElementById("orcamentoConta").disabled = true;
        document.getElementById("orcamentoOp").disabled = true;

      }else{
        document.getElementById("orcamentoBanco").disabled = false;
        document.getElementById("orcamentoAgencia").disabled = false;
        document.getElementById("orcamentoTipoConta").disabled = false;
        document.getElementById("orcamentoConta").disabled = false;
        document.getElementById("orcamentoOp").disabled = false;
      }
    }); 

    $("#orcamentoPrimeira").datepicker({
    	format: 'dd/mm/yyyy',
	    autoclose:true,
    });
   /* $("#orcamentoPedido").change(function(){

      //variavel que péga o valor do Numero de Pedido fornecido pelo onChange
      var nPedido = parseInt($(this).val());
      //varivael que pega os elementos do seletor
      var selectFornecedor = document.getElementById("orcamentoFornecedor");
      //variavel que carrega a busca no array de selectFornecedor de acordo com o nPedido
      var findArray = selectPedido.find(element => element.id_pedido === nPedido);

        //condicao que bloqueia e nao bloqueia o botao de Adicionar
        if(nPedido != 0){
          $("#adicionarOrcamento").removeClass('disabled');
        }else{
          $("#adicionarOrcamento").addClass('disabled');
        }
        //condicao que muda o valor da selecao Fornecedor se for 0
        if(nPedido === 0){
          $("#orcamentoFornecedor").val(0);
        }
        //passando valor de fornecedor da variavel findArray para a selecao Fornecedor
        $("#orcamentoFornecedor").val(findArray.id_fornecedor);
        
        //laço de repeticao que bloqueia ou nao as opcoes da selecao Fornecedor
        for(i=0;i<=selectFornecedor.options.length;i++){
         
          if(selectFornecedor.options[i].value != findArray.id_fornecedor){
            selectFornecedor.options[i].disabled = true;
          }else{
            selectFornecedor.options[i].disabled = false;
          }
        }
    }); */
    fornecedores(1);
});

  function fornecedores(count){
    var texto = "";

    if(count == 1 && countS2 < 4){
      countS2++;
    }else if(count == 0){
      countS2--;
    }

    if(countS2 < 4){
      texto = "Adicione informações de pagamento para inserir mais fornecedores!";
    }else{
      texto = "Você selecionou a quantidade máxima de fornecedores!";
    }

    

    $("#orcamentoFornecedor").select2({
      placeholder: "-- SELECIONE --",   
      allowClear: true,
      scrollAfterselect:true,
      maximumSelectionLength : countS2,
      language: {
        maximumSelected: function () {
            return texto;
        }
      }
    });

    

    $("#orcamentoFornecedor").on("select2:select", function (evt) {
      var element = evt.params.data.element;
      var $element = $(element);
      
      $element.detach();
      $(this).append($element);
      $(this).trigger("change");
    });

    
  }

$('#orcamentoFornecedor').on('select2:unselect', function (e) {
  var idfornecedor = e.params.data.id;

  $.ajax({
    data: {
      idOrcamento: idOrcamento,
      idfornecedor: idfornecedor,
      "_token": "{{ csrf_token() }}"
    },
    method: "get",
    url: '/delete/orcamento/fornecedor',
    success: function(result) {
      if(result == 1){
        fornecedores(0);
      }
      addColumn();
      
    },
    error: function(result){
        alert("ERRO!");
    }
  });
 
});


$(document).on('click', '#adicionarOrcamento', function(){
    $('#exampleModal').modal({
        backdrop:'static'
    });
});

$('#exampleModalPedido').modal({
    backdrop:'static',
    keyboard:false
});

$(window).load(function (nPedido) {

  $('#tableNPedido').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "fixedHeader": true,
        "ajax": {
          data: { 
            "_token": "{{ csrf_token() }}" 
          },
          method: "get",
          url: '/' + 'load' + '/' + 'numero' + '/' + 'pedido',
          dataType: "json",
        },
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
  },
      "columnDefs":[
                { "visible": true, "targets": [2]}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {

                    return '<input style="position:relative;left:10px;" required type="radio" class="form-check-input" name="radio" id="' + row.id_pedido + '">';

                }
            },
            {
                mRender: function (data, type, row) {
                    return row.id_pedido ;
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  return row.nome;
                }
            },
          ]
    }); 


    $('#tableOrcamento').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "scrollX": true,
        "fixedHeader": true,
        "ajax": {
          data: { 
            radio: function(){return radio;},
            "_token": "{{ csrf_token() }}" 
          },
          method: "get",
          url: '/' + 'load' + '/' + 'orcamento',
          dataType: "json",
        },
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
  },      
  "columnDefs":[
                { "visible": false, "targets": [2,3,4,5]}
               
             ],    
      "columns": [
            {
                mRender: function (data, type, row) {

                    return '<td id="id_'+row.descricao+'">'+row.descricao+'</td>' ;

                }
            },
            {
                mRender: function (data, type, row) {
                    return '<td id="id_'+row.quantidade+'">'+row.quantidade+'</td>' ;
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  //seleciona o id dos inputs e insere em um array, verificando antes se o mesmo já está ou não no array
                  var ide = "_"+'column2_'+row.quantidade+'_'+row.id_produto;

                  if(somaTotalColumn2.indexOf(ide) == -1){
                    produto.push(row.id_produto);
                    quantidade.push(row.quantidade);
                    somaTotalColumn2.push(ide);
                  }

                  $("#id_column2_"+row.quantidade+'_'+row.id_produto).mask("#.##0,00",{reverse:true});
                  
                  return '<div class="input-group m-b-sm"><span class="input-group-addon" id="basic-addon1">R$</span><input type="text" maxlength="13" onkeyup="somaValor()" id="id_column2_'+row.quantidade+'_'+row.id_produto+'" name="'+'column2_'+row.quantidade+row.id_produto+'" value=""></div>';
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  //seleciona o id dos inputs e insere em um array, verificando antes se o mesmo já está ou não no array
                  var ide = "_"+'column3_'+row.quantidade+'_'+row.id_produto;

                  if(somaTotalColumn3.indexOf(ide) == -1){
                    //produto.push(row.id_produto);
                    //quantidade.push(row.quantidade);
                    somaTotalColumn3.push(ide);
                  }
                  
                  $("#id_column3_"+row.quantidade+'_'+row.id_produto).mask("#.##0,00",{reverse:true});

                  return '<div class="input-group m-b-sm"><span class="input-group-addon" id="basic-addon1">R$</span><input type="text" maxlength="13" onkeyup="somaValor()" id="id_column3_'+row.quantidade+'_'+row.id_produto+'" name="'+'column3_'+row.quantidade+row.id_produto+'" value=""></div>';
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  //seleciona o id dos inputs e insere em um array, verificando antes se o mesmo já está ou não no array
                  var ide = "_"+'column4_'+row.quantidade+'_'+row.id_produto;

                  if(somaTotalColumn4.indexOf(ide) == -1){
                    //produto.push(row.id_produto);
                    //quantidade.push(row.quantidade);
                    somaTotalColumn4.push(ide);
                  }
                  
                  $("#id_column4_"+row.quantidade+'_'+row.id_produto).mask("#.##0,00",{reverse:true});

                  return '<div class="input-group m-b-sm"><span class="input-group-addon" id="basic-addon1">R$</span><input type="text" maxlength="13" onkeyup="somaValor()" id="id_column4_'+row.quantidade+'_'+row.id_produto+'" name="'+'column4_'+row.quantidade+row.id_produto+'" value=""></div>';
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  //seleciona o id dos inputs e insere em um array, verificando antes se o mesmo já está ou não no array
                  var ide = "_"+'column5_'+row.quantidade+'_'+row.id_produto;

                  if(somaTotalColumn5.indexOf(ide) == -1){
                    //produto.push(row.id_produto);
                    //quantidade.push(row.quantidade);
                    somaTotalColumn5.push(ide);
                  }

                  $("#id_column5_"+row.quantidade+'_'+row.id_produto).mask("#.##0,00",{reverse:true});
                  
                  return '<div class="input-group m-b-sm"><span class="input-group-addon" id="basic-addon1">R$</span><input type="text" maxlength="13" onkeyup="somaValor()" id="id_column5_'+row.quantidade+'_'+row.id_produto+'" name="'+'column5_'+row.quantidade+row.id_produto+'" value=""></div>';
                }
            },
          ],/*
          drawCallback: function () {
            var api = this.api();
            var table = document.getElementById('tableOrcamento');
            var data = table.getElementsByTagName('input');
            console.log(data); 
            $.each(data, function(i){
              $('#'+data[i].id).on('change', function() {
                //var id = this.value;
                var id1 = $('#15').val();
                var id2 = $('#30').val();
                var result = parseFloat(id1)+parseFloat(id2);
                $( api.table().footer() ).html('<tr><th>Subtotal</th><th></th><th>R$'+result+'</th></tr>');
              });
            });
          }*/
    });         
});

function somaValor(){
  var totalcolumn2 = 0;
  var totalcolumn3 = 0;
  var totalcolumn4 = 0;
  var totalcolumn5 = 0;
  
  for(var i=0;i<somaTotalColumn2.length;i++){
    var valor = $("#id"+somaTotalColumn2[i]).val();
    //console.log(valor);
    if(valor == NaN || valor == null || valor == ""){
      totalcolumn2 += 0;
    }else{
      valor = valor.replaceAll(".", "").replaceAll(",", ".");
      totalcolumn2 += parseFloat(valor);
    }
    $("th#footerResult1").html("R$ "+totalcolumn2.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ',')));
    $("#footerResult1").css("display","none");
  }


  for(var i=0;i<somaTotalColumn3.length;i++){
    var valor = $("#id"+somaTotalColumn3[i]).val();
    //console.log(valor);
    if(valor == NaN || valor == null || valor == ""){
      totalcolumn3 += 0;
    }else{
      valor = valor.replaceAll(".", "").replaceAll(",", ".");
      totalcolumn3 += parseFloat(valor);
    }
    $("th#footerResult2").html("R$ "+totalcolumn3.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ',')));
    $("#footerResult2").css("display","none");
  }

  for(var i=0;i<somaTotalColumn4.length;i++){
    var valor = $("#id"+somaTotalColumn4[i]).val();
    //console.log(valor);
    if(valor == NaN || valor == null || valor == ""){
      totalcolumn4 += 0;
    }else{
      valor = valor.replaceAll(".", "").replaceAll(",", ".");
      totalcolumn4 += parseFloat(valor);
    }
    $("th#footerResult3").html("R$ "+totalcolumn4.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ',')));
    $("#footerResult3").css("display","none");
  }

  for(var i=0;i<somaTotalColumn5.length;i++){
    var valor = $("#id"+somaTotalColumn5[i]).val();
    //console.log(valor);
    if(valor == NaN || valor == null || valor == ""){
      totalcolumn5 += 0;
    }else{
      valor = valor.replaceAll(".", "").replaceAll(",", ".");
      totalcolumn5 += parseFloat(valor);
    }
    $("th#footerResult4").html("R$ "+totalcolumn5.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ',')));
    $("#footerResult4").css("display","none");
  }


}

function addColumn() {

  produto.length = 0;
  quantidade.length = 0;
  somaTotalColumn2.length = 0;
  somaTotalColumn3.length = 0;
  somaTotalColumn4.length = 0;
  somaTotalColumn5.length = 0;

  var table = $('#tableOrcamento').DataTable();
  var count = 2;
  table.columns([2,3,4,5]).visible(false);
  
  var idfornecedor = $("#orcamentoFornecedor").val();

  //console.log(idfornecedor);

  if(idfornecedor != null){

    $.ajax({
      data: {
        idfornecedor: idfornecedor,
        idOrcamento: idOrcamento,
          "_token": "{{ csrf_token() }}"
      },
      method: "get",
      url: '/' + 'load' + '/' + 'fornecedor' + '/' + 'table',
      success: function(result) {
        
          $('#tableOrcamento').DataTable().ajax.reload();
          
          for(var i=0;i<result.length;i++){
            console.log(result[i].id_fornecedor);
            if(arrFornecedor.indexOf(result[i].id_fornecedor) > -1){
              console.log(result[i].id_fornecedor);
              table.column(count).visible(true);
              count++;
              $("#saveOrcamento").removeClass('disabled');
            }
            
            var soma = i + 2;
            $('#forn'+soma).html(result[i].razao_social);
            
          }

          

      },
      error: function(result){
              alert("ERRO!");
      }
    });
    

  }else{
    $("#saveOrcamento").addClass('disabled');
  }

}


function saveOrcamento(){

    var idfornecedor = ($("#orcamentoFornecedor").val());
    //console.log(idfornecedor);

        $("input[name='radio']").each(function() {
          if ($(this).prop("checked")) {
            nPedido = $(this).prop("id");
          }
    });

    var table = document.getElementById('tableOrcamento');
    var tableProduto = $("#produto").html();
    var data = table.getElementsByTagName('input');
    var arraySomaColumn2 = [];
    var arraySomaColumn3 = [];
    var arraySomaColumn4 = [];
    var arraySomaColumn5 = [];

    for(var i=0;i<somaTotalColumn2.length;i++){
      var val2 = $("#id"+somaTotalColumn2[i]).val();
      if(typeof val2 !== "undefined"){
        arraySomaColumn2.push(val2.replaceAll(".","").replaceAll(",","."));
      }
    }

    for(var i=0;i<somaTotalColumn3.length;i++){
      var val3 = $("#id"+somaTotalColumn3[i]).val();
      if(typeof val3 !== "undefined"){
        console.log(val3);
        arraySomaColumn3.push(val3.replaceAll(".","").replaceAll(",","."));
      }
    }

    for(var i=0;i<somaTotalColumn4.length;i++){
      var val4 = $("#id"+somaTotalColumn4[i]).val();
      if(typeof val4 !== "undefined"){
        arraySomaColumn4.push(val4.replaceAll(".","").replaceAll(",","."));
      }
    }

    for(var i=0;i<somaTotalColumn5.length;i++){
      var val5 = $("#id"+somaTotalColumn5[i]).val();
      if(typeof val5 !== "undefined"){
        arraySomaColumn5.push(val5.replaceAll(".","").replaceAll(",","."));
      }
    }
    //console.log();
    
    $.ajax({
        data: {
            idOrcamento: idOrcamento,
            idfornecedor: idfornecedor,
            valorColumn2: arraySomaColumn2,
            valorColumn3: arraySomaColumn3,
            valorColumn4: arraySomaColumn4,
            valorColumn5: arraySomaColumn5,
            produto: produto,
            quantidade: quantidade,
            nPedido: nPedido,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'orcamento' + '/' + 'compra',
        success: function(result) {
            alert("ORÇAMENTO REALIZADO COM SUCESSO!");
            location.reload();
        },
        error: function(result){
              alert("ERRO!");
        }
      });
    //var id1 = data[1].getAttribute( 'id' );
    //var id2 = data[2].getAttribute( 'id' );
    
};   

$(document).on("submit", "#frmOrcamentoPagamento", function(event) {

    event.preventDefault();

    $("input[name='radio']").each(function() {
          if ($(this).prop("checked")) {
            idPedido = $(this).prop("id");
          }
    });

    var idfornecedor = ($("#orcamentoFornecedor").val());
    //console.log(idfornecedor);
    if(idfornecedor.length > 1){
      var idfornecedor = [idfornecedor.pop()];
    }
    //console.log(idfornecedor);

    var orcamentoBanco = $('#orcamentoBanco').val();
    var orcamentoAgencia = $('#orcamentoAgencia').val();
    var orcamentoTipoConta = $('#orcamentoTipoConta').val();
    var orcamentoConta = $('#orcamentoConta').val();
    var orcamentoOp = $('#orcamentoOp').val();
    var orcamentoForma = $('#orcamentoForma').val();
    var orcamentoParcela = $('#orcamentoParcela').val();
    var orcamentoPrimeira = $('#orcamentoPrimeira').val();

      $.ajax({
        data: {
            idOrcamento: idOrcamento,
            idPedido: idPedido,
            idfornecedor: idfornecedor,
            orcamentoBanco: orcamentoBanco,
            orcamentoAgencia: orcamentoAgencia,
            orcamentoTipoConta: orcamentoTipoConta,
            orcamentoConta: orcamentoConta,
            orcamentoOp: orcamentoOp,
            orcamentoForma: orcamentoForma,
            orcamentoParcela: orcamentoParcela,
            orcamentoPrimeira: orcamentoPrimeira,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'pagamento' + '/' + 'compra',
        success: function(result) {
            //alert("SALVO COM SUCESSO!");
            $('#exampleModal').modal('hide');
            $("#saveOrcamento").removeClass('disabled');
            addColumn();
            fornecedores(1);
            document.getElementById("frmOrcamentoPagamento").reset();
        },
        error: function(result){
            if(result = 'SQLSTATE[23000]: Integrity constraint violation:'){
                alert('Pagamento já cadastrado!')
            }else{
                alert("ERRO!");
            }
        }
      });
}); 


function selecionePedido(){

  $("input[name='radio']").each(function() {
      if ($(this).prop("checked")) {
        radio = $(this).prop("id");
      }
  });

  if(radio.length >= 1){
    $.ajax({
          data: {
              pedido: radio,
              "_token": "{{ csrf_token() }}"
          },
          method: "post",
          url: '/' + 'salvar' + '/' + 'orcamento' + '/' + 'pedido',
          success: function(result) {
            var orcs = JSON.parse(result);
            
            idOrcamento = orcs.id;
            
            if(orcs.forns.length>0){   
              countS2 = orcs.forns.length;
              $("#orcamentoFornecedor").val(orcs.forns);
              $('#orcamentoFornecedor').trigger('change');
              fornecedores(1);
              addColumn();
            }

            //alert("SALVO COM SUCESSO!");
            $('#tableOrcamento').DataTable().ajax.reload();
            $('#exampleModalPedido').modal('hide');
            
          },
          error: function(result){
            alert("ERRO!");
          }
        });
  }else{
    alert('Selecione um N° Pedido!')
  }
}

</script>
@stop
