<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body class="page-header-fixed hover-menu"> 
        <main class="page-content content-wrap">
        
            <table class="table table-borderless text-center">
                
                    <?php $count = 0;?>
                    @foreach($infoQRCode as $key => $info)
                        <?php
                        
                        if($key % ($qntPorLinha) == 0){
                            echo "<tr>";
                            $count = 0;
                        }
                        $count++;
                        ?>
                       

                            
                        <td class="noBorder text-center" style="line-height: {{$tamFonte*1.5}}px;"> 
                            <div style="max-width:{{$tamQr*1.3}}px;">
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size($tamQr)->generate($info)) !!} ">
                                <br>
                                <span style="font-size:{{$tamFonte}};" class="text-r">{{$info}}</span>
                            </div>
                        </td>


                        <?php
                         if($count == $qntPorLinha){
                            echo "</tr>";
                        }
                        
                        ?>
                    @endforeach
                    
                    
                
            </table>
            
        </main><!-- Page Content -->
        
    
    </body>

  
    <style>

        .noBorder {
            border:none !important;
        }


        .text-r{
            overflow-wrap: break-word;
            word-wrap: break-word;
            
        }

        
        html{
            margin:0px 0px;    
        }
        
    </style>
</html>
