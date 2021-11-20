@extends('home')
@section('title', 'Relatório de Conflito de Horários')
@section('title2', 'Relatório de Conflito de Horários')
@section('content')
<button onClick="atualizaHorarios()" class="btn btn-success"><span class="fa fa-refresh "></span> Atualizar Horários</button>
<br><br>
<table class="display" style="width:100%" id="tableRelatorios"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">Armário Nº</th>
      <th scope="col">1º Funcionário</th>
      <th scope="col">2º Funcionário</th>
      <th scope="col">1º Horário</th>
      <th scope="col">2º Horário</th>
    </tr>
  </thead>
  <tbody>
    @foreach($relatorio as $r)
        <tr>
          <td>{{$r['rel2']->id_armario}}</td>  
          <td>{{$r['rel1']->id_usuario}} - {{$r['rel1']->nome}}</td>
          <td>{{$r['rel2']->id_usuario}} - {{$r['rel2']->nome}}</td>
          <td>{{$r['rel1']->entrada}} às {{$r['rel1']->saida}}</td>
          <td>{{$r['rel2']->entrada}} às {{$r['rel2']->saida}}</td>          
        </tr>
    @endforeach
  </tbody>
</table><!-- FIM TABELA -->
<script>
    $(window).load(function () {
        $('#tableRelatorios').DataTable({
          dom: 'Bfrtip',
          buttons: [
              'csvHtml5',
              'excelHtml5'
          ],
          "pageLength": 50, 
          "searching": true,
          "paging": true,
          "info": false,
          "scrollX": true,
          "language":{
            url:"/assets/plugins/datatables/js/Portuguese-Brasil.json"
          },
          "columnDefs":[
              { "orderable": false, "targets": 4}    
          ],
        });       
    });


function atualizaHorarios(){
  $.ajax({
    data: {
        "_token": "{{ csrf_token() }}"
    },
    method: "GET",
    url: "/atualiza_horarios",
    success: function(result) {
        alert("HORÁRIOS ATUALIZADOS COM SUCESSO!!");
        window.location.reload();
    },
    error: function(result){
      alert("ERRO!");
    }
  });
}


</script>
@stop
