<?php
    if(isset($_GET['excluir'])){
        $idExcluir = intval($_GET['excluir']);
        $selectImagem = MySql::connect()->prepare("SELECT capa FROM `tb_site.noticias` WHERE id= ?");
        $selectImagem->execute(array($_GET['excluir']));
        $imgem = $selectImagem->fetch()['capa'];
        Painel::deleteFile($imgem);
        Painel::deletar("tb_site.noticias",$idExcluir);
        Painel::redirect(INCLUDE_PATH_PAINEL.'gerenciar-noticias');
    }else if(isset($_GET['order']) && isset($_GET['id'])){
        Painel::orderItem("tb_site.noticias",$_GET['order'],$_GET['id']);

    }
    $pagineAtual = isset($_GET['pagina'])?(int)$_GET['pagina']: 1;
    $porPagina = 5;
    $servicos = Painel::selectAll("tb_site.noticias",($pagineAtual -1)*$porPagina,$porPagina);
?>
<div class="box-container w100">
    <h2 class="title"><i class="far fa-list-alt"></i> Listar Slides</h2>
    <hr>
    <div class="scroll">
        <div class="tabela-responciva depoimento">
        
                <div class="row">
                    <div class="col">
                        <span>Titulo:</span>
                    </div><!--col-->
                    <div class="col">
                        <span>Capa:</span>
                    </div><!--col-->
                    <div class="col1">
                         <span>#</span>
                    </div><!--col-->
                    <div class="col1">
                        <span>#</span>
                    </div><!--col-->
                    <div class="col1">
                        <span>#</span>
                    </div><!--col-->
                    <div class="col1">
                        <span>#</span>
                    </div><!--col-->
                    <div class="clear"></div>
                </div><!--row-->
                <?php foreach($servicos as $key =>$value){?>
                
                <div class="row">
                    <div class="col">
                        <span><?php echo $value['titulo'];?></span>
                    </div><!--col-->
                    <div class="col">
                        <span><img class="mini" src="<?php INCLUDE_PATH_PAINEL?>uploads/<?php echo $value['capa'];?>" alt=""></span>
                    </div><!--col-->
                    <!--botão de editar-->
                        <a href="<?php echo INCLUDE_PATH_PAINEL?>editar-noticias?id=<?php echo $value['id']; ?>"><div class="col1 editar"><i class="fas fa-pencil-alt"></i></div><!--col--></a> 
                    <!--botão de deletar-->                    
                        <a actionBtn="delete" href="<?php echo INCLUDE_PATH_PAINEL?>gerenciar-noticias?excluir=<?php echo $value['id']; ?>"><div class="col1 delete"><i class="fas fa-trash"></i></div><!--col--></a>  
                    <!--fim dos botoes-->
                    <a href="<?php echo INCLUDE_PATH_PAINEL?>gerenciar-noticias?order=up&id=<?php echo $value['id']; ?>"><div class="col1 local"><i class="fas fa-angle-up"></i></div><!--col--></a>
                    <!--botão de deletar-->                    
                        <a href="<?php echo INCLUDE_PATH_PAINEL?>gerenciar-noticias?order=dow&id=<?php echo $value['id']; ?>"><div class="col1 local"><i class="fas fa-angle-down"></i></div><!--col--></a>  
                    <!--fim dos botoes-->
                    <div class="clear"></div>
                </div><!--row-->
                <?php } ?>
                <div class="paginacao">
                <?php
                    $totalPaginas = ceil(count(Painel::selectAll("tb_site.noticias"))/$porPagina);
                    for($i =1; $i <= $totalPaginas; $i++){
                        if($i == $pagineAtual)
                            echo  '<a class="pag-select" href="'.INCLUDE_PATH_PAINEL.'gerenciar-noticias?pagina='.$i.'">'.$i.'</a>';
                        else
                            echo  '<a  href="'.INCLUDE_PATH_PAINEL.'gerenciar-noticias?pagina='.$i.'">'.$i.'</a>';

                    }
                ?>
                
                </div>
            </div><!--tabela-responciva-->
       </div>
</div>