<?php 
require_once 'init.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type"     content="text/html; charset=utf-8" />
        <link rel='stylesheet' type='text/css' href='css/classes.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/layout.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/layers.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='css/table.css' media='screen'/>
        <link rel='stylesheet' type='text/css' href='js/redmond/jquery-ui.custom.css' media='screen'/>
        <script type="text/javascript" src="js/jquery-latest.min.js"></script>
        <script type="text/javascript" src="js/jqueryui.min.js"></script>
        <script type="text/javascript" src="js/submit.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $( "#tabs" ).tabs({collapsible: true, event: "mouseover"}).find( ".ui-tabs-nav" ).sortable({ axis: "x" });
                var availableTags = [<?php echo $tags; ?>];
                $( "#tcons" ).autocomplete({
                    minLength: 0,
                    source: function( request, response ) {
                            response( $.ui.autocomplete.filter(
                                    availableTags, extractLast( request.term ) ) );
                    },
                    select: function( event, ui ) {
                            var terms = split( this.value );
                            // remove the current input
                            terms.pop();
                            // add the selected item
                            terms.push( ui.item.value );
                            // add placeholder to get the comma-and-space at the end
                            terms.push( "" );
                            this.value = terms.join( " " );
                            return false;
                    }
                });
            });
        </script>
        <script type="text/javascript" src="js/tags.js"></script>
        <?php if(geografico){ ?>
        <!--<script type="text/javascript" src="js/raphael.js"></script>
        <script type="text/javascript" src="js/raphael_example.js"></script>-->
        <?php } ?>
    </head>
    <body>
        <div id="tudo">
            <div id="listlayers" >
                    <div class="scroll">
                        <div>
                            Opções
                            <div id="opcoes">
                                
                                <a href="?action=getlink" id="getlink"><img src='img/link.png' alt="link"/></a>
                                <?php if(geografico){ ?><a href="merge.php?action=merge" target="__blank" id="merge">
                                    <img src='img/merge.png'/></a>
                                <?php }?>
                            </div>
                        </div>
                        <ul id="sortable">
                            <?php 
                                foreach($layers as $layer){
                                    $key     = base64_encode($layer);
                                    $link    = "?consulta=$key";
                                    $ver     = "<a href='$link&action=recuperaconsulta' class='action'><img src='img/btn_editar.png'/></a>";
                                    $apagar  = "<a href='$link&action=apagaconsulta' class='action'><img src='img/btn_excluir.png'/></a>";
                                    $acoes   = "$ver $apagar";
                                    echo "<li class='layer border'>
                                            <a href='$key' class='selecionar'>
                                                <div class='item bg bg-hover'>".  nl2br($layer)."</div>
                                            </a>
                                            <div class='acoes'>$acoes</div>
                                         </li>";
                                }
                            ?>
                        </ul>
                    </div>
            </div>
            <div id="consulta">
                <style>
                    a.tablink{
                        font-family:Verdana, Arial, sans-serif;
                        font-size:9px;
                    }
                </style>
                <div id="tabs">
                    <ul>
                        <li><a class='tablink' href="#tabs-1">Resultado</a></li>
                        <?php if(geografico){?><li><a class='tablink' href="#tabs-2">Mapa</a></li><?php }?>
                        <li><a class='tablink' href="#tabs-3">Schema</a></li>
                    </ul>
                    <div id="tabs-1">
                        <div id="mainlayer" class="mainlayer border bg"><table></table><?php if(isset($result)) echo $result; ?></div>
                    </div>
                    <?php if(geografico){ ?>
                    <div id="tabs-2">
                        <div id="maps" class="mainlayer border bg"><?php if(isset($map)) echo $map; ?></div>
                    </div>
                    <?php }?>
                    <div id="tabs-3">
                        <div id="schema" class="mainlayer border bg"><?php if(isset($schema)) echo $schema; ?></div>
                    </div>
                    
                    <div id="formulario" class="border bg">
                        <form action="?action=consultar" method="post" id="target">
                            <textarea name="consulta" id="tcons" class="border"><?php echo $first; ?></textarea>
                            <br/>
                            <input type="submit" value="consultar" id="other"/>
                        </form>
                    </div>
                </div>
                
                
            </div>
        </div>
    </body>
</html>