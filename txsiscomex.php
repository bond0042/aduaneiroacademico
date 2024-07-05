<?php


//Função para selecionar string que está entre duas strings
  //Primeiro parâmetro é a string que será pesquisada
  //Segundo parâmetro é a 'tag' de ínicio, ou seja, onde começa a string que queremos encontrar
  //Terceiro parâmetro é a 'tag' de fim, ou seja, onde termina a string que queremos encontrar
  function get_string_between($string, $start, $end){

      $string = ' ' . $string;

      $ini = strpos($string, $start);

      if ($ini == 0) return '';

      $ini += strlen($start);

      $len = strpos($string, $end, $ini) - $ini;

      return substr($string, $ini, $len);

    }

    function get_taxa_siscomex($start){
      $content = file_get_contents('https://www.aduaneiras.com.br/Informacoes/ObterTaxasDoDia?dia=' . $start);
      
      $array = json_decode($content);

      $taxas = $array->Result->Taxas ?? array();
      
      $data = array();
      foreach ($taxas as $obj) {
        if(in_array($obj->codigo, array("220", "978", "540", "425"))){
          $data[$obj->codigo] = [
            'nome' => $obj->nome,
            'taxa' => $obj->taxaFiscalFormatada
          ];
        }
      }

      return $data;
    }




      try {

        //Traz todo o conteúdo da página em questão como string
        $taxasAnteOntem = get_taxa_siscomex(date('Y-m-d', strtotime('-2 days')));
        $taxasOntem = get_taxa_siscomex(date('Y-m-d', strtotime('-1 days')));
        $taxasHoje = get_taxa_siscomex(date('Y-m-d'));
        $taxasAmanha = get_taxa_siscomex(date('Y-m-d', strtotime('+1 days')));

        $dataAnteOntem = date('d/m/Y', strtotime('-2 days'));
        $dataOntem = date('d/m/Y', strtotime('-1 days'));
        $dataHoje = date('d/m/Y');
        $dataAmanha = date('d/m/Y', strtotime('+1 days'));

        echo "<!-- Tabela SISCOMEX - INICIO --><thead>

          <tr>

            <th align=\"left\">MOEDAS</th>
            <th align=\"center\">{$dataAnteOntem}</th>
            <th align=\"center\">{$dataOntem}</th>
            <th align=\"center\">{$dataHoje}</th>
            <th align=\"center\">{$dataAmanha}</th>

          </tr>

          </thead>

          ";

          echo "<tbody>";

          foreach ($taxasHoje as $key => $value)
          {
            echo "<tr>";
            echo "<td>" . $value['nome'] . "</td>";
            echo "<td>". ($taxasAnteOntem[$key]['taxa'] ??'')."</td>";
            echo "<td>" . ($taxasOntem[$key]['taxa'] ?? '') . "</td>";
            echo "<td>" . $value['taxa'] . "</td>";
            echo "<td>" . ($taxasAmanha[$key]['taxa'] ?? '') . "</td>";
            echo "</tr>";
          }

          echo "</tbody>";

    } catch (Exception $e) {

        }

    ?>
    <!-- Tabela SISCOMEX - FIM -->  
    </table>