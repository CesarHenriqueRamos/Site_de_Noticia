<?php
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $dados = Painel::selectSimples("tb_site.noticias", $id);
    }else{
        Painel::alert('erro', 'Ocorreu Um Erro');
        Painel::redirect(INCLUDE_PATH_PAINEL.'listar-servico'); 
    }
?>

<div class="box-container w100">
    <h2 class="title"><i class="far fa-edit"></i> Editar Depoimento</h2>
    <hr>
 <?php
    if(isset($_POST['acao'])){
        //enviado o formulario
        $titulo = $_POST['titulo'];
        $imagem = $_FILES['imagem'];
        $imagem_atual = $_POST['imagem_atual'];
        $conteudo = $_POST['conteudo'];
        $categoria_id = $_POST['categoria_id'];
        $order_id = $_POST['order_id'];
        $id = $_GET['id'];
        $data = date('Y-m-d');
        $verificar = MySql::connect()->prepare("SELECT * FROM `tb_site.noticias` WHERE titulo = ? AND id != ?");
        $verificar->execute(array($titulo,$id));
        if($verificar->rowCount() == 1){
            Painel::alert('erro', 'Já Existe Uma Noticia'); 
        }else{
            //validação
            if($titulo == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Titulo');
            }else if($conteudo == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Conteudo');
            }else if(Painel::imagemValida($imagem) == false){
                $imagem = $imagem_atual;
                $arr = ['categoria_id'=>$categoria_id,'titulo'=>$titulo,'conteudo'=>$conteudo,'data'=>$data,'capa'=>$imagem,'order_id'=>$order_id,'nome_tabela'=>'tb_site.noticias','id'=>$id];
                Painel::update($arr);
                Painel::alert('sucesso', 'Cadastrado com Sucesso');
                $dados = Painel::selectSimples("tb_site.noticias", $id);
            }else{
                //função cadastra no banco de dados os dado
                $imagem = Painel::uploadFile($imagem);
                Painel::deleteFile($imagem_atual);
                $arr = ['categoria_id'=>$categoria_id,'titulo'=>$titulo,'conteudo'=>$conteudo,'data'=>$data,'capa'=>$imagem,'order_id'=>$order_id,'nome_tabela'=>'tb_site.noticias','id'=>$id];
                Painel::update($arr);
                Painel::alert('sucesso', 'Cadastrado com Sucesso');
                $dados = Painel::selectSimples("tb_site.noticias", $id);
            }
        }
        
    }
 ?>
    <form action="" method="post" enctype="multipart/form-data" id="editar-usuario">

    
   
        <div class="box-form">
            <label for="nome">Nome:</label>
            <input type="text" name="titulo" id="nome" value="<?php echo $dados['titulo'] ?>">
        </div>
        <div class="box-form">
            <label for="mensagem">Noticias:</label>
            <textarea class="tinymce" name="conteudo" id="conteudo" cols="30" rows="10" ><?php echo $dados['conteudo'] ?></textarea>
        </div>
        <div class="box-form">
            <label for="categoria">Categoria:</label>
            <select name="categoria_id" id="">
            <?php
            $sql = Painel::selectAll('tb_site.categorias');
            foreach($sql as $key => $value){
            ?>
                <option <?php if($value['id'] == $dados['categoria_id']) echo 'selected'; ?> value="<?php echo $value['id'] ?>"><?php echo $value['nome'] ?></option>
            <?php } ?>
            </select>
        </div>
        <div class="box-form">
            <label for="img">Imagem:</label>
            <input type="file" name="imagem">
            <input type="hidden" name="imagem_atual" value="<?php echo $dados['capa'] ?>">
        </div>
        <div class="box-form">
            <input type="hidden" name="id" value="<?php echo $dados['id']; ?>"> 
            <input type="hidden" name="order_id" value="<?php echo $dados['order_id']; ?>">     
            <input type="submit" name="acao" value="Editar">
        </div>
    </form>
</div>

