<!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- -->
  <link rel="shortcut icon" href="favicon.ico">

  <body>

    <div id="ptax">
      <table border="2">
        <?php
//Função para selecionar string que está entre duas strings
  //Primeiro parâmetro é a string que será pesquisada
  //Segundo parâmetro é a 'tag' de ínicio, ou seja, onde começa a string que queremos encontrar
  //Terceiro parâmetro é a 'tag' de fim, ou seja, onde termina a string que queremos encontrar
/*  function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }*/

    try {
    // setamos o mes e ano conforme o padrao do site do portaldefinancas, se nao trazer resultado e preciso verificar no site o novo padrao
        $mes = date('m');
        $ano = date('Y');
        $content = file_get_contents('http://www.portaldefinancas.com/js-mod/dolptax-' . $ano . '-' . $mes . '.js');

      //Caso ocorra algum erro, a página não foi encontrada etc
      if ($content === false) {
          // Handle the error
      } else {
        echo "<!-- Tabela PTAX - INICIO --><thead>
        <tr>
          <th colspan=\"5\" align=\"center\">TAXAS PTAX</th>
        </tr>
        <tr><td rowspan=\"2\" align=\"center\">Dia</td><td colspan=\"2\" align=\"center\">Euro</td><td colspan=\"2\" align=\"center\">Dólar</td></tr><tr><td>Compra</td><td>Venda</td><td>Compra</td><td>Venda</td></tr>
        </thead>
        ";

        $linhaDolar1 = get_string_between($content, "document.write('", "');"); //devido a ser um JS estamos trazendo tudo que esta entre o document.write
        $linhaDolar1 = get_string_between($linhaDolar1, "<tr class=\"", "</tr>");//trazendo a 1 linha do que foi conseguido no document.write que e o dia mais recente

        $toReplace = array("class=\"tr02\"", "class=\"tr03\"", "class=\"td04-mob\"", "tr02\">", "tr03\">", "tr02\"", "tr03\"", );//limpamos as strings desnecessarias
        foreach ($toReplace as $value) { // fazendo o replace das strings desnecessarias para ""vazio e trazendo somente o que esta na $linha1
          $linhaDolar1 = str_replace($value, "", $linhaDolar1);
        }
        echo "
        <tbody>
          {$linhaDolar1}
        </tbody>";
      }
  } catch (Exception $e) {
      }
    ?>
    <!-- Tabela PTAX - FIM -->  
    </table>
  </div>
  </body>
</html>