<div class="box-container w100" 

<?php
    verificaPermissaoMenu(2);
?>>
    <h2 class="title"><i class="fas fa-user-plus"></i> Cadastrar Categorias</h2>
    <hr>

    <form action="" method="post" enctype="multipart/form-data" >
 
	<?php
        if(isset($_POST['acao'])){
            //enviado o formulario
            $nome = $_POST['nome'];
            //validação
            if($nome == ''){
                Painel::alert('erro', 'É Necessário Preencher o Campo Login');
            }else{
                $slug = Painel::generateSlug($nome);
                $arr = ['nome'=>$nome,'slug'=>$slug,'order_id'=>'0','nome_tabela'=>'tb_site.categorias'];
                Painel::insert($arr);
                Painel::alert('sucesso', 'Cadastrado com Sucesso');
            }
        }
		?>
        <div class="box-form">
            <label for="nome">Nome da Categoria:</label>
            <input type="text" name="nome" id="nome">
        </div>
        <div class="box-form">            
            <input type="submit" name="acao" value="Cadastrar">
        </div>
    </form>
</div>

