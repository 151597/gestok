<?php
     date_default_timezone_set('America/Sao_Paulo');

     $data = new DateTime();
     $formatter = new IntlDateFormatter('pt_BR',
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::NONE,
                    'America/Sao_Paulo',          
                    IntlDateFormatter::GREGORIAN,
                    "d 'de' MMMM 'de' Y");
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body class="page-header-fixed hover-menu"> 
        <main class="page-content content-wrap">

            
            <div class="row" style="padding:10px;border:3px solid black;border-radius:1%;">
            <div  style="padding:10px;border:3px solid black;border-radius:1%;">
                Eu, {{ Auth::user()->name }}, declaro que estou enviando o(s) seguinte(s) ativos para a manutenção.
                <br>
                <br>
        
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Ativo</th>
                            <th>Nº de Série</th>x
                            <th>Patrimônio</th>x
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ativos as $ativo)
                            <tr>
                                <td>
                                    <?=$ativo->nome?>
                                </td>
                                <td>
                                    <?=$ativo->descricao?>
                                </td>
                                <td>
                                    <?=$ativo->serie?>
                                </td>
                                <td>
                                    <?=$ativo->patrimonio?>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                O(s) ativo(s) estão sob os cuidados de:
                _______________________________________________________
                <br><br>
                da empresa:
                ____________________________________________________________________________
                <br><br>
                <div style="text-align: center;">
                    Curitiba, <?=$formatter->format($data)?>
                    <br><br>
                    _____________________________
                    <br>
                    {{ Auth::user()->name }}
                </div>
                <img src="assets/images/Gestok-logo.jpg"  height="30" style="float:right;"> 
            </div>
            </div>

        </main><!-- Page Content -->
        
    
    </body>
</html>
