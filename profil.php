<?php
require "../Social_network_clone/layouts/header.php";

if (!isset($_SESSION['users'])) {
    header("Location: login.php");
    exit;
}

$users = null;
$singlePost = null;

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
    $users = getById($id, $pdo, "users");
}

if (isset($_FILES['profil'])) {
    uploadProfil($_FILES, $_SESSION['users']->id, $pdo, "profil");
    header("Location: profil.php?user_id={$_SESSION['users']->id}");
    exit;
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    likes($_SESSION['users']->id, $post_id, $pdo);
    header("Location: profil.php?user_id={$_GET['user_id']}");
    exit;
}

if (isset($_POST['commentaire'])) {

    $postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
    $content = isset($_POST['comments']) ? $_POST['comments'] : null;
    $comment_id = isset($_GET['Comment_id']) ? $_GET['Comment_id'] : null;

    if (is_null($post_id)) {
        updateEntity("comments", [
            "content" => $content,
        ], $pdo, $comment_id);
        header("Location: profil.php?user_id=$id");
        exit;
    }
    updateEntity("comments", [
        "content" => $content,
    ], $pdo, $comment_id);
    header("Location: profil.php?user_id=$id&post_id=$postId");
    exit;
}

if (isset($_POST['commenter'])) {

    $postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
    $content = isset($_POST['comments']) ? $_POST['comments'] : null;
    $user_id = isset($_SESSION['users']) ? $_SESSION['users']->id : null;
    $id = $_GET['user_id'];

    if (isset($_GET['id_post'])) {

        insert("comments", [
            "user_id" => $user_id,
            "post_id" => $_GET['id_post'],
            "content" => $content,
        ], $pdo);
        header("Location: profil.php?user_id=$id");
    }
    insert("comments", [
        "user_id" => $user_id,
        "post_id" => $postId,
        "content" => $content,
    ], $pdo);

    header("Location: profil.php?user_id=$id&post_id=$postId");
}

if (isset($_POST['post'])) {
    if (isset($_FILES['picture']) && !empty($_FILES['picture'])) {
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_name = $_FILES['picture']['name'];
        $Path = "../Facebook/images/" . $file_name;
        if (move_uploaded_file($file_tmp, $Path)) {
            $data = [
                "user_id" => $_SESSION['users']->id,
                "picture" => $file_name,
                "content" => $_POST['content'],
            ];
            insert("posts", $data, $pdo);
        }
    }
}

if (isset($_POST['editInfo'])) {
    updateEntity('users', [
        "email" => $_POST['email'],
        "username" => $_POST['username']
    ], $pdo, $_GET['user_id']);
}

if (isset($_GET['delete_comment']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $delete_comment = $_GET['delete_comment'];
    deleteItems($delete_comment, $pdo, "comments");
    header("location:profil.php?user_id=$user_id");
    exit;
}

if (isset($_GET['id_Post']) && isset($_GET['user_id'])) {
    $post_id = $_GET['id_Post'];
    $user_id = $_GET['user_id'];
    likes($_SESSION['users']->id, $post_id, $pdo);
    header("Location: profil.php?user_id=$user_id&post_id=$post_id");
    exit;
}

if (isset($_GET['edit_id'])) {
    $edit_post = getById($_GET['edit_id'], $pdo, "posts");
}

if (isset($_POST['edit'])) {

    if (isset($_FILES['picture']) && !empty($_FILES['picture']['tmp_name'])) {

        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_name = $_FILES['picture']['name'];
        $destination_folder = "../Social_network_clone/images/";
        $destination_path = $destination_folder . $file_name;
        if (move_uploaded_file($file_tmp, $destination_path)) {

            $content = $_POST['content'];
            $post_id = $_GET['modif'];
            $user_id = $_GET['user_id'];
            $data = [
                "picture" => $file_name,
                "content" => $content
            ];
            updateEntity("posts", $data, $pdo, $post_id);
            header("Location: profil.php?user_id=$user_id");
            exit();
        }
    }
}


?>


<!-- <div class="row py-5 px-4"> -->
<div class="col-md-13 mx-auto">
    <!-- Profile widget -->
    <div class="overflow-hidden">
        <div class="px-4 pt-0 pb-4 cover">
            <?php if (isset($id) and $id != $_SESSION['users']->id) : ?>
                <div class="media align-items-end profile-head">
                    <div class="profile mr-3">
                        <img src="/images/<?php echo  $users->profil  ?>" alt="Profile Picture" width="300" class="rounded mb-2 mt-5 img-thumbnail">
                        <input class="d-none" type="file" name="" id="profil" onchange="submitForm()">
                        <?php if (isset($id) and $id == $_SESSION['users']->id) : ?>
                            <a href="#" class="btn btn-primary ">Edit profile</a>
                        <?php endif ?>
                    </div>
                    <div class="media-body mb-5 text-white">
                        <h4 class="mt-0 mb-0"><?php echo $users->username ?></h4>
                        <p class="small mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>New York
                        </p>
                    </div>
                </div>
            <?php else : ?>
                <div class="media align-items-end profile-head">
                    <div class="profile mr-3">
                        <form enctype="multipart/form-data" id="form_file" action="" method="post" is="auto-submit">
                            <label for="profil" style="position: relative; display: inline-block;">
                                <img src="/images/<?php echo $users->profil  ?>" alt="Profile Picture" width="300" class="rounded mb-2 mt-5 img-thumbnail">
                                <i style="color: white; position:absolute; bottom: 30px; right: 20px; font-size: 20px;" class="fa-solid fa-camera"></i>
                            </label>
                            <input style="display: none;" type="file" name="profil" name="profil" onchange="submitForm()" id="profil">
                        </form>
                    </div>
                    <div class="media-body mb-5 text-white">
                        <h4 class="mt-0 mb-0"><?php echo $users->username  ?></h4>
                        <p class="small mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>New York
                        </p>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div style="background-color:#f0f2f5" class="bg-light p-5 d-flex justify-content-center text-center">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <h5 class="font-weight-bold mb-0 d-block">215</h5>
                    <small class="text-muted">
                        <i class="fas fa-image mr-1"></i>Photos
                    </small>
                </li>
                <li class="list-inline-item">
                    <h5 class="font-weight-bold mb-0 d-block">745</h5>
                    <small class="text-muted">
                        <i class="fas fa-user mr-1"></i>Followers
                    </small>
                </li>
                <li class="list-inline-item">
                    <h5 class="font-weight-bold mb-0 d-block">340</h5>
                    <small class="text-muted">
                        <i class="fas fa-user mr-1"></i>Following
                    </small>
                </li>
            </ul>
        </div>

        <?php if (isset($_GET['edit_id'])) : ?>
            <div class="modal fade  shadow-lg" id="photoModal-1" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="photoModalLabel">Modifier le Post</h5>
                            <span style="cursor: pointer;" aria-hidden="true" data-dismiss="modal"><a href="index.php"><i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i></a></span>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <form action="profil.php?modif=<?= $_GET['edit_id'] ?>&user_id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="mb-3">
                                                <div class="col-span-full">
                                                    <label for="cover-photo" class="block text-sm font-medium text-gray-900">Cover photo</label>
                                                    <div class="mt-2 p-4 border border-dashed border-gray-300 rounded">
                                                        <div id="text-center">
                                                            <img style="width:100%;height:400px; object-fit: cover;" src="images/<?= $edit_post->picture ?>" alt="Preview Image">
                                                        </div>
                                                        <div class="mt-4">
                                                            <label for="file-upload" class="btn btn-danger">
                                                                Upload a file
                                                                <input id="file-upload" name="picture" type="file" onchange="previewImage(event)" class="d-none">
                                                            </label>
                                                            <p class="text-muted">Ajouter des photos</p>
                                                        </div>
                                                        <p class="text-xs text-muted">PNG, JPG, GIF up to 10MB</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name="content" id="textInput" rows="3"><?= $edit_post->content ?></textarea>
                                        </div>
                                        <button style="width: 100%;" name="edit" type="submit" class="btn btn-warning">Poster</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <!-- <div class="container"> -->
        <div class="page-inner no-page-title">
            <!-- start page main wrapper -->
            <div id="main-wrapper">
                <div class="row">
                    <div class="col-lg-5 col-xl-5">
                        <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card card-white  grid-margin">
                            <div class="card-heading clearfix">
                                <h4 class="card-title">User Profile</h4>
                            </div>
                            <?php if (isset($id) and $id != $_SESSION['users']->id) : ?>
                                <div class="card-body user-profile-card mb-3">
                                    <img src="/images/<?php echo $users->profil  ?>" class="user-profile-image rounded-circle" alt="" />
                                    <h4 class="text-center h6 mt-2"><?= $users->username ?></h4>
                                </div>
                            <?php else : ?>
                                <div class="card-body user-profile-card mb-3">
                                    <img src="/images/<?php echo $users->profil  ?>" class="user-profile-image rounded-circle" alt="" />
                                    <h4 class="text-center h6 mt-2"><?= $users->username ?></h4>
                                    <a class="btn btn-primary btn-sm btn-block" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit profile</a>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="card-title">Modifier information personnel</h5>
                                                    <a href="profil.php?user_id=<?= $_SESSION['users']->id ?>">
                                                        <i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i>
                                                    </a>
                                                </div>
                                                <!-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Est natus aspernatur laboriosam minima, pariatur sequi fugiat deserunt laudantium provident quam vel quaerat culpa molestias repudiandae numquam exercitationem sit impedit itaque. -->
                                                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                                    <form method="Post" action="">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <input type="email" class="form-control" value="<?= $users->email ?>" name="email" id="inputEmail4" placeholder="Email">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <input type="text" class="form-control" value="<?= $users->username ?>" name="username" id="inputPassword4" placeholder="Username">
                                                            </div>
                                                        </div>
                                                        <button style="float: left;" name="editInfo" type="submit" class="btn btn-warning">Modifier</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <hr />
                            <div class="card-heading clearfix mt-3">
                                <h4 class="card-title">Contact Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0 text-muted">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Email:</th>
                                                <td><?= $users->email ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phone:</th>
                                                <td>(+44) 123 456 789</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Address:</th>
                                                <td>74 Guild Street 542B, Great North Town MT.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body mb-3">
                                <?php if($_SESSION['users']->id == $id):?>
                                    <button id="delete_account" data-userId="<?= $_SESSION['users']->id ?>" class="btn btn-danger mr-3">Supprimer votre compte</button>
                                    <button id="change password" data-bs-toggle="modal" data-bs-target="#exampleModal-password" data-userId="<?= $_SESSION['users']->id ?>" class="btn btn-danger">Modifier votre Mot de passe</button>
                                <?php endif ?>
                                <div class="modal fade" id="exampleModal-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="card-title">Modifier votre password </h5>
                                                <a href="profil.php?user_id=<?= $_SESSION['users']->id ?>">
                                                    <i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i>
                                                </a>
                                            </div>
                                            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                                <form method="Post" action="">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <input type="password" class="form-control" name="password" placeholder="Password">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <input type="password" class="form-control" name="confirmer_password" id="inputPassword4" placeholder="Confirmer password">
                                                        </div>
                                                    </div>
                                                    <button style="float: left;" name="pass" type="submit" class="btn btn-warning">Modifier password</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-5 scrollable-block " id="scrollable-container">
                        <?php if (isset($id) and $id == $_SESSION['users']->id) : ?>
                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card mb-4">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="comment-box d-flex justify-content-center">
                                                <img src="/images/113948823.png" class="profile-picture" alt="Image de profil">
                                                <div data-toggle="modal" data-target="#photoModal" style="border-radius: 20px; background-color: #f0f2f5; cursor: pointer;" onmouseover="this.style.backgroundColor='rgb(234, 234, 234)'; this.style.color='white';" onmouseout="this.style.backgroundColor='#f0f2f5'; this.style.color='black';" class="w-100 pl-2 pt-2 ">
                                                    <p style="font-size:20px;" class="text-dark">Quoi de neuf , <?= $users->username ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <div class="text-center d-flex justify-content-center">
                                                    <img height="24" width="24" alt="" src="/images/video.png">
                                                    <a style="font-weight: bold;" class="text-decoration-none text-dark ml-2" href="">Live video</a>
                                                </div>
                                                <div class="text-center  d-flex justify-content-center">
                                                    <img height="24" width="24" alt="" src="/images/photov.png">
                                                    <a style="font-weight: bold;" class="text-decoration-none text-dark  ml-2" href="#" data-toggle="modal" data-target="#photoModal">Photo|Video</a>
                                                    <div class="modal fade  shadow-lg" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="photoModalLabel">Creer un nouveau post</h5>
                                                                    <span style="cursor: pointer;" aria-hidden="true" data-dismiss="modal"><i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i></span>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                                <div class="form-group">
                                                                                    <div class="mb-3">
                                                                                        <div class="col-span-full">
                                                                                            <label for="cover-photo" class="block text-sm font-medium text-gray-900">Cover photo</label>
                                                                                            <div class="mt-2 p-4 border border-dashed border-gray-300 rounded">
                                                                                                <div id="text-center">

                                                                                                </div>
                                                                                                <div class="mt-4">
                                                                                                    <label for="file-upload" class="btn btn-primary">
                                                                                                        Upload a file
                                                                                                        <input id="file-upload" name="picture" type="file" onchange="previewImage(event)" class="d-none">
                                                                                                    </label>
                                                                                                    <p class="text-muted">Ajouter des photos</p>
                                                                                                </div>
                                                                                                <p class="text-xs text-muted">PNG, JPG, GIF up to 10MB</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <textarea class="form-control" name="content" id="textInput" rows="3"></textarea>
                                                                                </div>
                                                                                <button style="width: 100%;" name="post" type="submit" class="btn btn-primary">Poster</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center d-flex justify-content-center">
                                                    <img height="24" width="24" alt="" src="/images/fell.png">
                                                    <a style="font-weight: bold;" class="text-decoration-none text-dark ml-2" href="">Feeling|Activité</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card card-white grid-margin">
                                <div style="font-weight: bolder;font-size:25px">Publication</div>
                            </div>
                        <?php else : ?>
                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card card-white grid-margin">
                                <div style="font-weight: bolder;font-size:25px">Publication</div>
                            </div>
                        <?php endif ?>
                        <?php if (JointurePostTableByUserId($pdo, $id) == null) : ?>
                            <div style="font-weight: bolder;font-size:25px;text-align:center">Aucune publication disponible</div>
                        <?php else : ?>
                            <?php foreach (JointurePostTableByUserId($pdo, $id) as $post) : ?>
                                <div class="profile-timeline">
                                    <ul class="list-unstyled">
                                        <li class="timeline-item">
                                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card  grid-margin">
                                                <div class="card-body">
                                                    <div class="content d-flex">
                                                        <div class="timeline-item-header">
                                                            <a href="profil.php?user_id=<?= $post->user_id ?>"><img src="/images/<?= $post->profil ?>" alt="" /></a>
                                                            <p style="font-size: 20px;font-weight:600"><?= $post->username ?></p>
                                                        </div>
                                                        <?php if($_SESSION['users']->id == $id):?>
                                                            <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <small><i style="color: black; font-size: 15px;" class="fa-solid fa-ellipsis"></i></small>
                                                            </a>
                                                        <?php endif ?>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                            <li>
                                                                <a class="dropdown-item" href="profil.php?edit_id=<?= $post->id ?>&user_id=<?= $id ?>">
                                                                    <i class="fas fa-edit"></i> Modifier la publiaction
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="fas fa-trash-alt"></i> Supprimer la publication
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="timeline-item-post">
                                                        <p class="text-dark"><?= $post->content ?></p>
                                                        <div style="margin-bottom: 10px;" class="card">
                                                            <img src="/images/<?= $post->picture ?>" alt="">
                                                        </div>
                                                        <div class="bbbb d-flex justify-content-end">
                                                            <a href=""><?= CountCommentByPostId($pdo, $post->id) ?> <i class="fa-solid fa-comment"></i></a>
                                                            <a style="margin-left: 20px;" href="#"><i class="fa fa-share"></i> 20</a>
                                                        </div>
                                                        <hr>
                                                        <div class="timeline-options d-flex justify-content-around">
                                                            <?php if (!checkLikeForUser($pdo, $post->id, $_SESSION['users']->id)) : ?>
                                                                <a href="profil.php?user_id=<?= $id ?>&id=<?= $post->id ?>"><i class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                            <?php else : ?>
                                                                <a href="profil.php?user_id=<?= $id ?>&id=<?= $post->id ?>"><i style="color: red;" class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                            <?php endif ?>
                                                            <a style="cursor: pointer;" class="comment-button"><i class="fa fa-comment"></i>Commenter</a>
                                                            <a id="share" class="dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false" href="#"><i class="fa fa-share"></i>Partager</a>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                                <li><button class="dropdown-item" type="button">Partager Maintenant (Public)</button></li>
                                                                <li><button class="dropdown-item" type="button">Partager sur le Fil</button></li>
                                                                <li><button class="dropdown-item" type="button">Partager le profil d'un amis</button></li>
                                                            </ul>
                                                        </div>
                                                        <?php if (CountCommentByPostId($pdo, $post->id) > 1) : ?>
                                                            <div class="showAllcomment">
                                                                <a class="show" href="#" style="color:#000;font-weight:bold;cursor:pointer" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $post->id ?>" data-postid="<?= $post->id ?>" data-userid="<?= $id  ?>">Voir plus de commentaire</a>
                                                            </div>
                                                            <br>
                                                        <?php endif ?>
                                                        <div class="modal fade" id="exampleModal-post-<?php echo $post->id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="photoModalLabel">Publication de <?= JointurePostTableByPostId($pdo, $_GET['post_id'])->username ?></h5>
                                                                        <a href="profil.php?user_id=<?= $id ?>">
                                                                            <i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                                                        <div class="profile-timeline">
                                                                            <ul class="list-unstyled">
                                                                                <li class="timeline-item">
                                                                                    <div class="card card-white grid-margin">
                                                                                        <div class="card-body">
                                                                                            <div class="timeline-item-header">
                                                                                                <a href="#"><img src="/images/<?= JointurePostTableByPostId($pdo, $_GET['post_id'])->profil ?>" alt="" /></a>
                                                                                                <p><?= $post->username ?></p>
                                                                                                <small>3 hours ago</small>
                                                                                            </div>
                                                                                            <div class="timeline-item-post">
                                                                                                <p class="text-dark"><?= $post->content ?></p>
                                                                                                <div class="card">
                                                                                                    <img src="/images/<?= JointurePostTableByPostId($pdo, $_GET['post_id'])->picture ?>" alt="">
                                                                                                </div>
                                                                                                <div class="timeline-options d-flex justify-content-around">
                                                                                                    <?php if (!checkLikeForUser($pdo, $post->id, $_SESSION['users']->id)) : ?>
                                                                                                        <a href="profil.php?user_id=<?= $id ?>&id_Post=<?= $post->id ?>"><i class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                                                                    <?php else : ?>
                                                                                                        <a href="profil.php?user_id=<?= $id ?>&id_Post=<?= $post->id ?>"><i style="color: red;" class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                                                                    <?php endif ?>
                                                                                                    <a style="cursor: pointer;" class="comment-button"><i class="fa fa-comment"></i>Commenter</a>
                                                                                                    <a href="#"><i class="fa fa-share"></i>Partager</a>
                                                                                                </div>
                                                                                                <?php foreach (CommentByPostId(JointurePostTableByPostId($pdo, $_GET['post_id'])->id, $pdo) as $comment) : ?>
                                                                                                    <?php if (isset($_GET['Comment_id']) && $_GET['Comment_id'] == $comment->id) : ?>
                                                                                                        <form action="profil.php?user_id=<?php echo $id ?>&id_post=<?= $post->id ?>&Comment_id=<?= $comment->id ?>" style="margin-top: 20px; width:100%" method="post" class="col-12 mb-3">
                                                                                                            <div class="comment-box">
                                                                                                                <img src="/images/<?= $_SESSION['users']->profil ?>" class="profile-picture" alt="Image de profil">
                                                                                                                <div class="comment-input">
                                                                                                                    <textarea name="comments" class="form-control comment-textarea" placeholder="Écrivez un commentaire" style="height: 100px;" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"><?= getById($_GET['Comment_id'], $pdo, "comments")->content ?></textarea>
                                                                                                                    <button type="submit" style="display: block;" name="commentaire" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    <?php else : ?>
                                                                                                        <div class="timeline-comment d-flex">
                                                                                                            <div class="timeline-comment-header">
                                                                                                                <img src="/images/<?= $comment->profil ?>" alt="" />
                                                                                                            </div>
                                                                                                            <p style="background-color:#EBF2FA; border-radius: 10px; display: inline-block; padding: 10px; color:#000 ; margin-left:5px">
                                                                                                                <span style="font-weight: bold;"><?= $comment->username ?></span><br>
                                                                                                                <?= $comment->content ?>
                                                                                                            </p>
                                                                                                            <?php if ($_SESSION['users']->id == $comment->user_id) : ?>
                                                                                                                <div style="margin-left: 5px;">
                                                                                                                    <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                                        <i class="fa-solid fa-ellipsis"></i>
                                                                                                                    </a>
                                                                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                                                                        <li>
                                                                                                                            <a class="dropdown-item" href="profil.php?Comment_id=<?= $comment->id ?>&post_id=<?= $post->id ?>&user_id=<?= $id ?>">
                                                                                                                                <i class="fas fa-edit"></i> Modifier le commentaire
                                                                                                                            </a>
                                                                                                                        </li>
                                                                                                                        <li>
                                                                                                                            <a class="dropdown-item" href="profil.php?delete_comment=<?= $comment->id ?>&post_id=<?= $post->id ?>&user_id=<?= $id ?>&user_id=<?= $id ?>">
                                                                                                                                <i class="fas fa-trash-alt"></i> Supprimer
                                                                                                                            </a>
                                                                                                                        </li>
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            <?php endif ?>
                                                                                                        </div>
                                                                                                    <?php endif ?>
                                                                                                <?php endforeach ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form style="margin-top: 20px; width:100%" action="profil.php?user_id=<?php echo $id ?>&post_id=<?= $post->id ?>" method="post" class="col-12">
                                                                            <div class="comment-box">
                                                                                <img src="/images/<?= $_SESSION['users']->profil ?>" class="profile-picture" alt="Image de profil">
                                                                                <div class="comment-input">
                                                                                    <textarea name="comments" class="form-control custom-textarea" placeholder="Écrivez un commentaire" onclick="heightTextarea(this)" oninput="autoResize(this)" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"></textarea>
                                                                                    <button type="submit" style="display: block;" name="commenter" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php foreach (CommentByPostId($post->id, $pdo, 1) as $comment) : ?>
                                                            <?php if (isset($_GET['Comment_id']) && $_GET['Comment_id'] == $comment->id) : ?>
                                                                <form action="profil.php?user_id=<?php echo $id ?>&Comment_id=<?= $comment->id ?>" style="margin-top: 20px; width:100%" method="post" class="col-12">
                                                                    <div class="comment-box">
                                                                        <img src="/images/<?= $_SESSION['users']->profil ?>" class="profile-picture" alt="Image de profil">
                                                                        <div class="comment-input">
                                                                            <textarea name="comments" class="form-control comment-textarea" placeholder="Écrivez un commentaire" style="height: 100px;" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"><?= getById($_GET['Comment_id'], $pdo, "comments")->content ?></textarea>
                                                                            <button type="submit" style="display: block;" name="commentaire" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            <?php else : ?>
                                                                <div class="timeline-comment d-flex">
                                                                    <div class="timeline-comment-header">
                                                                        <img src="/images/<?= $comment->profil ?>" alt="" />
                                                                    </div>
                                                                    <p style="background-color:#EBF2FA; border-radius: 10px; display: inline-block; padding: 10px; color:#000 ; margin-left:5px">
                                                                        <span style="font-weight: bold;"><?= $comment->username ?></span><br>
                                                                        <?= $comment->content ?>
                                                                    </p>
                                                                    <?php if ($_SESSION['users']->id == $comment->user_id) : ?>
                                                                        <div style="margin-left: 5px;">
                                                                            <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="fa-solid fa-ellipsis"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                                <li>
                                                                                    <a class="dropdown-item" href="profil.php?Comment_id=<?= $comment->id ?>&user_id=<?= $id ?>">
                                                                                        <i class="fas fa-edit"></i> Modifier le commentaire
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a class="dropdown-item" href="profil.php?delete_comment=<?= $comment->id ?>&user_id=<?= $id ?>">
                                                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endif ?>
                                                                </div>
                                                            <?php endif ?>
                                                        <?php endforeach ?>
                                                        <form action="profil.php?user_id=<?php echo $id ?>&id_post=<?= $post->id ?>" style="margin-top: 20px; width:100%" method="post" class="col-12">
                                                            <div class="comment-box">
                                                                <img src="/images/<?= $_SESSION['users']->profil ?>" class="profile-picture" alt="Image de profil">
                                                                <div class="comment-input">
                                                                    <textarea name="comments" class="form-control comment-textarea" placeholder="Écrivez un commentaire" onclick="heightTextarea(this)" oninput="autoResize(this)" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"></textarea>
                                                                    <button type="submit" style="display: block;" name="commenter" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <div class="col-lg-12 col-xl-3">

                    </div>
                </div>
                <!-- Row -->
                <!-- </div> -->

            </div>
        </div>
        <?php require "../Social_network_clone/layouts/footer.php"; ?>