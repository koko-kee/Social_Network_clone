<?php


function insert($tableName, $data, $conn): int
{
    $columns = implode(", ", array_keys($data));
    $placeholder = implode(",", array_fill(0, count(array_values($data)), "?"));
    $stmt = $conn->prepare("INSERT INTO $tableName ($columns) VALUES($placeholder)");
    $stmt->execute(array_values($data));
    return $conn->lastInsertId();
}


function updateEntity($table, $data, $conn, $id): void
{   
    $setClause = [];
    $values = [];
    foreach ($data as $key => $value){
        $setClause[] = "$key = ?";
        $values[] = $value;
    }
    $values[] = $id;
    $trimClause = implode(",",$setClause);
    $stmt= $conn->prepare("UPDATE $table SET $trimClause WHERE id = ?");
    $stmt->execute($values);
}

function get($entityName, $conn){

    $stmt = $conn->prepare("SELECT * FROM $entityName ORDER BY id desc");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getById($id,$conn,$entityName = "users"){
    $stmt = $conn->prepare("SELECT * FROM $entityName WHERE id = $id");
    $stmt->execute();
    return $stmt->fetch();
}

function deleteItems($id,$conn,$entityName){
    $stmt = $conn->prepare("DELETE  FROM $entityName WHERE id = $id");
    $stmt->execute();
    return $stmt->fetch();
}


function required($data): ?int
{
    $errors = [];
    array_pop($data);
    foreach ($data as $key => $value){
        if (empty($value)) {
            $errors[$key] = "Le champ $key est obligatoire.";
        }
    }
    if(empty($errors)){
        return null;
    }
    $_SESSION['message'] = $errors;
    return 1;
}


function JointurePost($conn)
{
    $stmt = $conn->prepare("SELECT p.id , p.picture, p.content , p.date ,p.statut , u.profil , u.username ,p.user_id FROM posts p INNER JOIN users u ON p.user_id = u.id ORDER BY p.id DESC");
    $stmt->execute();
    return $stmt->fetchAll();
} 


function JointureShare($conn)
{
    $stmt = $conn->prepare("SELECT p.id , p.picture, p.content , u.profil , u.username ,p.user_id FROM posts p INNER JOIN share u ON p.user_id = u.id ORDER BY p.id DESC");
    $stmt->execute();
    return $stmt->fetchAll();
} 




function JointurePostTableByUserId($conn, $id)
{
    $stmt = $conn->prepare("SELECT p.id, p.picture, p.content, u.profil, u.username, p.user_id FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE u.id = ? ORDER BY p.id DESC");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return null;
    }
}



function JointurePostTableByPostId($conn , $id)
{
    $stmt = $conn->prepare("SELECT p.id , p.picture, p.content , u.profil , u.username ,p.user_id FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.id = ? ORDER BY p.id DESC");
    $stmt->execute([$id]);
    return $stmt->fetch();
}



function CommentByPostId($post_id, $conn , $limit = 0)
{
    if(($limit > 0)){

        $stmt = $conn->prepare("SELECT c.id , u.profil, u.username, c.content , c.user_id FROM comments c
        INNER JOIN posts p ON c.post_id = p.id
        INNER JOIN users u ON u.id = c.user_id
        WHERE c.post_id = ? ORDER BY c.id DESC LIMIT  $limit
      ");
    }
    else{
        $stmt = $conn->prepare("SELECT c.id , u.profil, u.username, c.content , c.user_id FROM comments c
        INNER JOIN posts p ON c.post_id = p.id
        INNER JOIN users u ON u.id = c.user_id
        WHERE c.post_id = ? ORDER BY c.id DESC 
  ");
    }
    $stmt->execute([$post_id]);
    return $stmt->fetchAll();
}




function CountCommentByPostId($conn,$post_id)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM comments WHERE post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchColumn();
}


function login(string $email, string $password, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user->password)) {
        return $user;
    }
    return null;
}


function checkLikeForUser($conn , $id_post , $id_user)
{
    $stmt = $conn->prepare("SELECT id  FROM likes WHERE user_id  = ? and post_id = ?");
    $stmt->execute([$id_user,$id_post]);
    if($stmt->rowCount() <= 0)
    {
     return false;
    }else{
      return true;
    }
}


function countTableById($post_id, $conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchColumn();
}

function countTable($conn,$table)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM  $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}
function likes($id_user, $post_id, $conn)
{
    $stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $id_user]);
    $likes = $stmt->fetch();

    if ($stmt->rowCount() <= 0) {
        insert("likes", [
            "user_id" => $id_user,
            "post_id" => $post_id,
        ], $conn);
    } else {
        $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $id_user]);
    }
}


function uploadProfil($data ,$user_id,$conn,$name)
{
    $file_tmp =  $data[$name]['tmp_name'];
    $file_name = $data[$name]['name'];
    $Path = "../Facebook/images/".$file_name;
    move_uploaded_file($file_tmp,$Path);
    updateEntity("users",["profil" => $file_name],$conn,$user_id);
}


function saveUserInFile($user_id, $conn)
{
    $user = getById($user_id, $conn);
    $userData = "$user->username,$user->username,$user->email,$user->password,$user->created_at" . PHP_EOL;
    file_put_contents("RegisterFile.txt", $userData, FILE_APPEND);
}


function getItems($pdo, $tableName, array $concat = null, $value = null, $numberPerPage = 0, $offset = 0) {

    $query = is_null($concat) && is_null($value) ? "SELECT * FROM $tableName LIMIT $numberPerPage" : "SELECT p.id , p.picture, p.content , u.profil , u.username ,p.user_id FROM posts p INNER JOIN users u ON p.user_id = u.id ORDER BY p.id DESC ";
    if (!is_null($concat) && !is_null($value)){
        $query .= " WHERE CONCAT(" . implode(',', $concat) . ") LIKE '%" . $value ."%'";
    }

    if ($offset > 0){
        $query .= " OFFSET $offset";
    }
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>