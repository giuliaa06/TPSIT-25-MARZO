<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni");
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function view(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE id = " . $args['id']);
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

    public function create(Request $request, Response $response, $args){
      $body = json_decode($request->getBody()->getContents(), true);
      
      
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      $result = $mysqli_connection->query("INSERT INTO alunni (nome, cognome) VALUES ('" . $body['nome'] . "', '" . $body['cognome'] . "' )");
      //$results = $result->fetch_all(MYSQLI_ASSOC);
  
      $response->getBody()->write(json_encode($results));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }

    public function update(Request $request, Response $response, $args) {
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      $body = json_decode($request->getBody()->getContents(), true);
      $id = $args['id']; 
      $nome = $body["nome"];
      $cognome = $body["cognome"];
      $stmt = $mysqli_connection->prepare("UPDATE alunni SET nome = ?, cognome = ? WHERE id = ?");
      $stmt->bind_param("ssi", $nome, $cognome, $id);
      $stmt->execute();
      
      // Controllo
      if ($stmt->affected_rows > 0) {
          $response->getBody()->write(json_encode(["message" => "Alunno aggiornato con successo"]));
          return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } else {
          // Se non è stato aggiornato nulla (ad esempio, l'ID non esiste)
          $response->getBody()->write(json_encode(["message" => "Nessun alunno trovato con l'ID fornito"]));
          return $response->withHeader("Content-type", "application/json")->withStatus(404);
      }
  } 

  public function destroy(Request $request, Response $response, $args){
    $body = json_decode($request->getBody()->getContents(), true);

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $query = "DELETE FROM alunni WHERE id = " .$args['id'];
    $result = $mysqli_connection->query($query);

    if($result){
      $response->getBody()->write(json_encode(["message" => "Alunno eliminato"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }else{
      $response->getBody()->write(json_encode(["message" => "Alunno non eliminato"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
  }
}
