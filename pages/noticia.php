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
<section class="pg-conteudo-single">
<div class="container">
        <div class="box-noticia">
<?php  if(Painel::logado()){
        //echo '<h3>Pode Comentar</h3>';
                //inserção dos commentarios na data base
                if(isset($_POST['acao'])){
                    $nome = $_SESSION['nome'];
                    $id_noticia = $_GET['id'];
                    $comentario = $_POST['mensagem'];
                    $sql = MySql::connect()->prepare("INSERT INTO `tb_site.comentario` VALUES(null,?,?,?)");
                    $sql->execute(array($id_noticia,$nome,$comentario));
                    echo '<script>alert("Comentario Cadastardo com Sucesso")</script>';
                  
                }
        ?>
    <form action="" method="post" id="comentario">
        <div class="form-container">
            <input type="text" name="nome" id="" value="<?php echo $_SESSION['nome'];?>" disabled>
        </div>
        <div class="form-container">
            <label for="mensagem">Digite Seu Comentario:</label>
            <textarea name="mensagem" id="" cols="30" rows="10" placeholder="Digite Seu Comentario..."></textarea>
        </div>
        <div class="form-container">
            <input type="submit" name="acao" value="Comentar">
        </div>
        
    </form>
    <?php }else{
        echo '<h4>Não Pode Comentar, para comentar efetue o <a href="'.INCLUDE_PATH.'painel/">login</a></h4>';

        ?>
        <form action="" method="post" id="comentario">
        <div class="form-container">
            <label for="mensagem">Digite Seu Comentario:</label>
            <textarea name="mensagem" id="" cols="30" rows="10" placeholder="Digite Seu Comentario..." disabled></textarea>
        </div>
        <div class="form-container">
            <input type="submit" value="Comentar" disabled>
        </div>
        
    </form>
    <?php }
    ?>
    <div class="resposta">
        <h3>Comentar Existentes</h3>
        <?php 
        $sql = MySql::connect()->prepare("SELECT * FROM `tb_site.comentario` WHERE noticia_id= ?");
        $sql->execute(array($_GET['id']));
        $dados = $sql->fetchAll();
        foreach($dados as $key => $value){
        ?>
        <div class="box-comentario">
            <h5><?php echo $value['nome'];?></h5>
            <p><?php echo $value['comentario'];?></p>
        </div>
        <?php } ?>
    </div>
      </div>
        </div>
</section>