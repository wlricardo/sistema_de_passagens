<meta charset="UTF-8">
<h3>📋 Inserir Veículo</h3>
<form name="dadosVeiculo" action="crud_veiculos.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Marca</td>
                <td><input type="text" name="marca" required /></td>
            </tr>
            <tr>
                <td>Modelo</td>
                <td><input type="text" name="modelo" required /></td>
            </tr>
            <tr>
                <td>Poltrona (Configuração)</td>
                <td><input type="text" name="poltrona" placeholder="Ex: Executivo 42" required /></td>
            </tr>
            <tr>
                <td>Tipo</td>
                <td>
                    <select name="tipo" required>
                        <option value="">Selecione o tipo</option>
                        <option value="Convencional">Convencional</option>
                        <option value="Executivo">Executivo</option>
                        <option value="Leito">Leito</option>
                    </select>
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Adicionar" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>
