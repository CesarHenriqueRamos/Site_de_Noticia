<div class="box-container w100" 

<?php
    verificaPermissaoMenu(2);
?>>
    <h2 class="title"><i class="fas fa-user-plus"></i> Cadastrar Noticia</h2>
    <hr>

    <form action="" method="post" enctype="multipart/form-data" >
 
	<?php
        if(isset($_POST['acao'])){
            //enviado o formulario
            $titulo = $_POST['titulo'];
            $imagem = $_FILES['capa'];
            $conteudo = $_POST['conteudo'];
            $categoria_id = $_POST['categoria'];
            $data = date('Y-m-d');
            //validação
            if($titulo == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Titulo');
            }else if($conteudo == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Conteudo');
            }else if(Painel::imagemValida($imagem) == false){
                Painel::alert('erro', 'Insira Uma Imagem Valida');
            }else{
                //função cadastra no banco de dados os dado
                $imagem = Painel::uploadFile($imagem);
                $arr = ['categoria_id'=>$categoria_id,'titulo'=>$titulo,'conteudo'=>$conteudo,'data'=>$data,'capa'=>$imagem,'order_id'=>'0','nome_tabela'=>'tb_site.noticias'];
                Painel::insert($arr);
                Painel::alert('sucesso', 'Cadastrado com Sucesso');
            }
        }
		?>
        <div class="box-form">
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" id="titulo">
        </div>
        <div class="box-form">
            <label for="conteudo">Conteudo:</label>
            <textarea class="tinymce"  name="conteudo" id="conteudo" cols="30" rows="10"></textarea>
        </div>
        <div class="box-form">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="">
            <?php
            $sql = Painel::selectAll('tb_site.categorias');
            foreach($sql as $key => $value){
            ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['nome'] ?></option>
            <?php } ?>
            </select>
        </div>
        <div class="box-form">
            <label for="img">Imagem:</label>
            <input type="file" name="capa" id="img">
        </div>
        <div class="box-form">            
            <input type="submit" name="acao" value="Cadastrar">
        </div>
    </form>
</div>

