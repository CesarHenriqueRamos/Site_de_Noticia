<?php
$id = $_GET['id'];
$noticia = Painel::select('tb_site.noticias','id=?', $id);
foreach($noticia as $key => $value){
?>
<section class="pg-conteudo-single">
    <div class="container">
        <div class="box-noticia">
            <h2><?php echo $value['titulo']; ?></h2>
            <div class="pg-noticia">
                <img src="<?php echo INCLUDE_PATH_PAINEL?>uploads/<?php echo $value['capa'];?>" alt="">
            </div>
            <p><?php echo $value['conteudo'];?></p>
            </div>
        </div>
<?php }?> 

</section>
