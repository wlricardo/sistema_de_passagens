<meta charset="UTF-8">
<h3>📋 Inserir Nova Rota</h3>
<form name="dadosRota" action="crud_rotas.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome da Rota</td>
                <td><input type="text" name="nome" placeholder="Ex: São Paulo x Curitiba" required /></td>
            </tr>
            <tr>
                <td>Cidade de Origem</td>
                <td>
                    <select name="cidade_origem_id" required>
                        <option value="">Selecione a origem</option>
                        <option value="1">São Paulo (SP)</option>
                        <option value="2">Rio de Janeiro (RJ)</option>
                        <option value="3">Curitiba (PR)</option>
                        <option value="4">Fortaleza (CE)</option>
                        <option value="5">Recife (PE)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Cidade de Destino</td>
                <td>
                    <select name="cidade_destino_id" required>
                        <option value="">Selecione o destino</option>
                        <option value="1">São Paulo (SP)</option>
                        <option value="2">Rio de Janeiro (RJ)</option>
                        <option value="3">Curitiba (PR)</option>
                        <option value="4">Fortaleza (CE)</option>
                        <option value="5">Recife (PE)</option>
                    </select>
                </td>
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
