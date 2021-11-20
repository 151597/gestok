@extends('home')
@section('title', 'Importação de CSV')
@section('title2', 'Importação de CSV')
@section('content')

<span>
    Os campos "alfa_ativo" e "alfa_setor" devem ser preenchidos com o código Alfa dos mesmos. Tal código é visível, 
    e editável, na tela de cadastro de <strong><a target="_blank" href="{{route('cadastro.tipoativo')}}">tipo de ativo</a></strong> e <strong><a target="_blank" href="{{route('cadastro.setores')}}">setor</a></strong>.
    <br>
    O campo "empresa" deve ser preenchido com: <strong>CAIXA</strong> ou <strong>GESTOK</strong>
    <br>
    O campo "situacao" deve ser preenchido com: 
    <br>
    <strong>BOM</strong> para "Bom", <strong>RUIM</strong> para "Ruim - gera conserto", <strong>ESTRAGADO</strong> para "Estragado - sem conserto" ou <strong>NAO</strong> para "Não avaliado"
    <br><br>
    A importação deve ser feita exclusivamente com o arquivo CSV disponível abaixo:
    <br>
    <a href="/template.csv" class="btn btn-success"><span class="fa fa-download"></span> Baixar CSV</a>
    <br><br>
    A ordem dos campos deve ser:
    <br>
    <strong>alfa_ativo | empresa | alfa_setor | gps | descricao | numero_serie | patrimonio | ramal | situacao</strong>
    <br><br>
    Um exemplo de como poderia ficar, caso o código alfa do tipo de ativo <strong>Mouse P2</strong> fosse <strong>MOSP2</strong> e do setor <strong>Gestok Matriz</strong> fosse <strong>PLANMTZ</strong>:
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>alfa_ativo</th>
                <th>empresa</th>
                <th>alfa_setor</th>
                <th>gps</th>
                <th>descricao</th>
                <th>numero_serie</th>
                <th>patrimonio</th>
                <th>ramal</th>
                <th>situacao</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>MOSP2</td>
                <td>GESTOK</td>
                <td>PLANMTZ</td>
                <td>5150</td>
                <td>MOS001</td>
                <td>OU812</td>
                <td>1984</td>
                <td>3097</td>
                <td>BOM</td>
            </tr>
        </tbody>
    </table>

    <strong>ATENÇÃO: <span style="color:red;">Não utilize</span></strong> caracteres especiais (à, á, ã, ç, etc). 
    <br>
    O upload estará passível de erro.
    

</span>
<br><br><br>
<!-- FORMULÀRIO -->
<div class="text-center">
  <form id="frmCSV" class="form-group" action="" enctype='multipart/form-data'>
    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
    <label  for="csvFile"><strong>Selecione o arquivo CSV para upload:</strong></label>
    <input style="padding-bottom:5px;display: block; margin:auto;" type="file" id="csvFile" name="csvFile" accept=".csv" required>
    <button type="submit" id="saveAjuste" class="btn btn-success"><span class="fa fa-upload"></span> Enviar</button>
  </form><!-- FIM FORMULÀRIO -->
</div>

<script>

$(document).on("submit", "#frmCSV", function(event) {
    event.preventDefault();

      $.ajax({
        data: new FormData(this),
        cache:false,
        contentType:false,
        processData:false,
        method: "post",
        url: '/salvar/csv',
        success: function(result) {
            alert(JSON.parse(result));
            document.getElementById("frmCSV").reset();
        },
        error: function(result){
          str = result.responseJSON.message;
          
          if(str.includes('SQLSTATE[23000]')){

            alert("Campo obrigatório nulo ou com informação inválida!");

          }else if(str.includes('SQLSTATE[HY000]') || str.includes('SQLSTATE[01000]')){

            alert("Caractere alfabético em campo numérico!");

          }else if(str.includes('Malformed UTF-8 characters')){

            alert('Caractere especial não aceito!');

          }else{

            alert(str);
            
          }
          
        }
      });
});  


</script>


@stop
