<?php
	if (isset($_POST['key'])){

		$conn = new mysqli('localhost', 'root','','db_php_ajax');

		if ( $_POST['key'] == 'getRowData'){
			$rowID = $conn->real_escape_string($_POST['rowID']);
			$sql = $conn->query("SELECT nome_cidade, desc_curta, desc_longa FROM cidade WHERE id = '$rowID'");
			$data = $sql->fetch_array();
			$jsonArray = array(
				'countryName' => $data["nome_cidade"],
				'shortDesc' => $data["desc_curta"], 
				'longDesc' => $data["desc_longa"],  
			);

			exit(json_encode($jsonArray));		
		}
			
		if ( $_POST['key'] == 'getExistingData'){
			$start = $conn->real_escape_string($_POST['start']);
			$limit = $conn->real_escape_string($_POST['limit']);

			$sql = $conn->query("SELECT id,nome_cidade FROM cidade LIMIT $start, $limit");
			if ($sql->num_rows > 0){
				$response = "";

				while ($data = $sql->fetch_array()) {
					$response .= '
						<tr>
							<td>'.$data["id"].'</td>
							<td id="country_'.$data["id"].'">'.$data["nome_cidade"].'</td>
							<td>
								<input type="button" onclick="viewORedit('.$data["id"].', \'edit\')" value="Editar" class="btn btn-primary">
								<input type="button" onclick="viewORedit('.$data["id"].', \'view\')" value="Ver" class="btn btn btn-secondary">
								<input type="button" onclick="deleteRow('.$data["id"].')" value="Deletar" class="btn btn-danger">
							</td> 
						</tr> 
					';
				}
				exit($response);
			}else{
				exit('reachedMax');
			}	
		}

		$rowID = $conn->real_escape_string($_POST['rowID']);

		if ($_POST['key'] == 'deleteRow'){
			$conn->query("DELETE FROM cidade WHERE id='$rowID'");
			exit('A cidade foi deletada!');
		}

		$name = $conn->real_escape_string($_POST['name']);
		$shortDesc = $conn->real_escape_string($_POST['shortDesc']);
		$longDesc = $conn->real_escape_string($_POST['longDesc']);
		

		if ($_POST['key'] == 'updateRow'){
			$conn->query("UPDATE cidade SET nome_cidade = '$name', desc_curta='$shortDesc', desc_longa='$longDesc' WHERE id = '$rowID'");
			exit('Informações atualizadas!');
		}
			
		if ($_POST['key'] == 'addNew'){
			$sql = $conn->query("SELECT id FROM cidade WHERE nome_cidade = '$name'");

			if ($sql->num_rows > 0){
				exit("Já existe uma cidade com este nome!");
			}else{
				$conn->query("INSERT INTO cidade (nome_cidade, desc_curta, desc_longa)
							 VALUES ('$name', '$shortDesc', '$longDesc')");
				exit('Cidade inserida!');
			}
		}
	}

?>
