<?php
require_once 'login.php';
include_once 'crud_cidades.php';

// Busca a lista de todas as cidades cadastradas no banco de dados
$cidades_disponiveis = listarCidades();
?>
<meta charset="UTF-8">
<h3>📋 Inserir Nova Rota</h3>

<form name="dadosRota" action="crud_rotas.php" method="POST">
    <table border="1" cellpadding="5">
        <tbody>
            <tr>
                <td>Nome da Rota</td>
                <td><input type="text" name="nome" placeholder="Ex: São Paulo x Curitiba" required /></td>
            </tr>
            
            <!-- SEÇÃO DE ORIGEM -->
            <tr>
                <td>Cidade de Origem</td>
                <td>
                    <select name="cidade_origem_id" id="origem" onchange="atualizarEstado(this.value, 'estado_origem')" required>
                        <option value="">Selecione a origem</option>
                        <?php foreach ($cidades_disponiveis as $cidade): ?>
                            <option value="<?= $cidade['id'] ?>"><?= htmlspecialchars($cidade['nome_cidade']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Estado de Origem</td>
                <!-- Campo readonly impede que o usuário mude manualmente, mantendo a integridade do banco -->
                <td><input type="text" id="estado_origem" placeholder="Selecione a cidade primeiro" readonly style="background-color: #eee;" /></td>
            </tr>

            <!-- SEÇÃO DE DESTINO -->
            <tr>
                <td>Cidade de Destino</td>
                <td>
                    <select name="cidade_destino_id" id="destino" onchange="atualizarEstado(this.value, 'estado_destino')" required>
                        <option value="">Selecione o destino</option>
                        <?php foreach ($cidades_disponiveis as $cidade): ?>
                            <option value="<?= $cidade['id'] ?>"><?= htmlspecialchars($cidade['nome_cidade']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Estado de Destino</td>
                <td><input type="text" id="estado_destino" placeholder="Selecione a cidade primeiro" readonly style="background-color: #eee;" /></td>
            </tr>

            <tr>
                <td>Tempo estimado de viagem</td>
                <td><input type="text" name="tempo_viagem" placeholder="Ex: 06:30" required /></td>
            </tr>
            <tr>
                <td>Valor Base (R$)</td>
                <td><input type="number" step="0.01" name="valor_base" required /></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Adicionar Rota" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>

<!-- SCRIPT JAVASCRIPT QUE FAZ A BUSCA EM TEMPO REAL -->
<script>
function atualizarEstado(cidadeId, inputIdDestino) {
    const inputEstado = document.getElementById(inputIdDestino);
    
    // Se o usuário selecionar a opção padrão (vazia), limpa o campo do estado
    if (cidadeId === "") {
        inputEstado.value = "";
        return;
    }

    // Faz uma requisição silenciosa em segundo plano para o arquivo PHP de busca
    fetch(`buscar_estado.php?cidade_id=${cidadeId}`)
        .then(response => response.text())
        .then(nomeEstado => {
            // Insere o nome do estado retornado diretamente na caixa de texto correspondente
            inputEstado.value = nomeEstado;
        })
        .catch(error => {
            console.error('Erro ao buscar o estado:', error);
            inputEstado.value = "Erro ao carregar";
        });
}
</script>
