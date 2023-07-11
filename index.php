<?php
require "../Social_network_clone/layouts/header.php";
define("ITEM_PER_PAGE", 1);
$count = countTable($pdo, 'posts');
$page = ceil($count /  ITEM_PER_PAGE);

if (!isset($_SESSION['users'])) {
    header("location: login.php");
    exit;
}

if (isset($_GET['post_id'])) {
    $id_post = $_GET['post_id'];
    likes($_SESSION['users']->id, $id_post, $pdo);
    header("location:index.php");
}

if (isset($_POST['post'])) {

    if (isset($_FILES['picture']) && !empty($_FILES['picture'])) {
        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_name = $_FILES['picture']['name'];
        $Path = "../Social_network_clone/images/" . $file_name;
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

if (isset($_POST['edit'])) {

    if (isset($_FILES['picture']) && !empty($_FILES['picture']['tmp_name'])) {

        $file_tmp = $_FILES['picture']['tmp_name'];
        $file_name = $_FILES['picture']['name'];
        $destination_folder = "../Social_network_clone/images/";
        $destination_path = $destination_folder . $file_name;
        if (move_uploaded_file($file_tmp, $destination_path)) {

            $content = $_POST['content'];
            $post_id = $_GET['modif'];
            $data = [
                "picture" => $file_name,
                "content" => $content
            ];
            updateEntity("posts", $data, $pdo, $post_id);
            header('Location: index.php');
            exit();
        }
    }
}

if (isset($_POST['comments'])) {

    $post_id = isset($_GET['id_post']) ? $_GET['id_post'] : null;
    $user_id = isset($_SESSION['users']) ? $_SESSION['users']->id : null;
    $content = isset($_POST['comments']) ? $_POST['comments'] : null;
    if ($post_id && $user_id && $content) {
        insert("comments", [
            "post_id" => $post_id,
            "user_id" => $user_id,
            "content" => $content,
        ], $pdo);
    }
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if ($id) {
        header("location:index.php?id=$id");
        exit;
    } else {
        header("location:index.php");
        exit;
    }
}




if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $posts = JointurePostTableByPostId($pdo, $id);
}


if (isset($_GET['edit_comment'])) {
    $comment_id = $_GET['edit_comment'];
    $edit_comment = getById($comment_id, $pdo, 'comments');
}


if (isset($_POST['edit_comment'])) {
    $comment = $_POST['comments'];
    updateEntity('comments', [
        "content" => $comment
    ], $pdo, $_GET['edit_comment']);
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if ($id) {
        header("location:index.php?id=$id");
        exit;
        header("location:index.php");
        exit;
    }
}


if (isset($_GET['delete_comment']) && isset($_GET['id'])) {
    $delete_comment = $_GET['delete_comment'];
    $id = $_GET['id'];
    deleteItems($delete_comment, $pdo, "comments");
    header("location:index.php?id=$id");
    exit;
}


if (isset($_GET['delpost'])) {
    $delpost = $_GET['delpost'];
    deleteItems($delpost, $pdo, "posts");
    header("location:index.php");
    exit;
}

$picture = getById($_SESSION['users']->id, $pdo)->profil;


if (isset($_GET['edit_id'])) {
    $edit_post = getById($_GET['edit_id'], $pdo, "posts");
}
?>

<style>
    .story.story1 {
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.5)), url(images/<?php echo $picture; ?>) no-repeat center center /cover;
    }
</style>
<div class="page-inner no-page-title mt-5">
    <!-- Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem pariatur nostrum suscipit tempore aspernatur atque. Ex fugit ab placeat officiis harum, dolore reiciendis assumenda enim facilis ipsam voluptatem soluta a! -->
    <!-- start page main wrapper -->
    <?php if (isset($_GET['edit_id'])) : ?>
        <span data-toggle="modal" data-target="#photoModal-2" style="display:none;"></span>
        <div class="modal fade  shadow-lg" id="photoModal-2" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">Modifier le Post</h5>
                        <span style="cursor: pointer;" aria-hidden="true" data-dismiss="modal"><a href="index.php"><i style="font-size: 35px;" class="fa-solid fa-circle-xmark"></i></a></span>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form action="index.php?modif=<?= $_GET['edit_id'] ?>" method="POST" enctype="multipart/form-data">
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
                                    <button style="width: 100%;" name="edit" type="submit" class="btn btn-warning">Modifier le post</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
    <div id="main-wrapper">
        <div class="row">
            <div style="position: fixed; left: 0;" class="col-lg-6 col-xl-4">
                <div class="grid-margin" style="border-radius: 10px; border:none; background-color: #f2f0f5;">
                    <div style="background-color: #f2f0f5;" class="card-body">
                        <div class="col-md-6 d-flex align-items-center mb-3 p-3" style="cursor: pointer;">
                            <a href="#" class="d-block mr-3"><i class="fa-solid fa-house"></i></a>
                            <span class="text-center" style="font-size: 1.2rem;font-weight:600">Home</span>
                        </div>
                        <a href="profil.php?user_id=<?= $_SESSION['users']->id ?>" class="d-block  mr-3 text-decoration-none ">
                            <div class="col-md-7 d-flex align-items-center mb-3 p-3" style="cursor: pointer;">
                                <img src="/images/<?= $users->profil ?>" alt="Image" class="rounded-circle" style="width: 40px; height: 40px;">
                                <span class="text-center d-block ml-2 text-dark" style="font-size: 1.2rem; font-weight: 600;"><?= $users->username ?></span>
                            </div>
                        </a>
                        <hr>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-3" style="cursor: pointer;">
                            <a href="#" class="d-block mr-3"><i class="fa-solid fa-user-group"></i></a>
                            <span class="text-center" style="font-size: 1.2rem;font-weight:600">Groups</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-3" style="cursor: pointer;">
                            <a href="#" class="d-block mr-3"><i class="fa-solid fa-store"></i></a>
                            <span class="text-center" style="font-size: 1.2rem;font-weight:600">Marketplace</span>
                        </div>
                        <hr>
                        <div class="col-md-6 d-flex align-items-center mb-3 p-3" style="cursor: pointer;">
                            <a href="#" class="d-block mr-3"><i class="fa-solid fa-circle-play"></i></a>
                            <span class="text-center" style="font-size: 1.2rem;font-weight:600">Watch</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mb-4 p-3" style="cursor: pointer;">
                            <a href="#" class="d-block mr-3"><i class="fa-solid fa-plus"></i></a>
                            <span class="text-center" style="font-size: 1.2rem;font-weight:600">More</span>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-lg-5 col-xl-5 scrollable-block container">
                <div class="content-area">
                    <div class="story-gallery">
                        <div class="story story1">
                            <img src="images/upload.png" alt="">
                            <p>Post Story</p>
                        </div>
                        <div class="story story2">
                            <img src="images/member-1.png" alt="">
                            <p>Alison</p>
                        </div>
                        <div class="story story3">
                            <img src="/images/member-2.png" alt="">
                            <p>Jackson</p>
                        </div>
                        <div class="story story4">
                            <img src="images/113948823.png" alt="">
                            <p>Samona</p>
                        </div>
                        <div class="story story5">
                            <img src="images/member-4.png" alt="">
                            <p>John</p>
                        </div>
                    </div>
                </div>
                <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <div class="comment-box d-flex justify-content-center">
                                    <a class="d-block" href="profil.php?user_id=<?= $users->id ?>">
                                        <img src="/images/<?= $users->profil ?>" class="profile-picture" alt="Image de profil">
                                    </a>
                                    <div data-toggle="modal" data-target="#photoModal-3" style="border-radius: 20px; background-color: #f0f2f5; cursor: pointer;" onmouseover="this.style.backgroundColor='rgb(234, 234, 234)'; this.style.color='white';" onmouseout="this.style.backgroundColor='#f0f2f5'; this.style.color='black';" class="w-100 pl-2 pt-2 ">
                                        <p style="font-size:20px;" class="text-dark">Quoi de neuf , <?= $users->username ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div class="text-center  d-flex justify-content-center">
                                        <img height="24" width="24" alt="" src="/images/video.png">
                                        <a style="font-weight: bold;" class="text-decoration-none text-dark ml-2" href="">Video en direct</a>
                                    </div>
                                    <div class="text-center  d-flex justify-content-center">
                                        <img height="24" width="24" alt="" src="/images/photov.png">
                                        <a href="#" style="font-weight: bold;" class="text-decoration-none text-dark ml-2" data-toggle="modal" data-target="#photoModal-3">Photo / Video</a>
                                        <!-- Modale de la photo -->
                                        <div class="modal fade  shadow-lg" id="photoModal-3" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-bold" id="photoModalLabel">Creer un nouveau post</h5>
                                                        <span style="cursor: pointer;" aria-hidden="true" data-dismiss="modal"><i style="font-size: 35px;color:crimson" class="fa-solid fa-circle-xmark"></i></span>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <form action="index.php" method="POST" enctype="multipart/form-data">
                                                                    <div class="form-group">
                                                                        <div class="mb-3">
                                                                            <div class="col-span-full">
                                                                                <label for="cover-photo" class="block text-sm font-medium text-gray-900">Cover photo</label>
                                                                                <div class="mt-2 p-4 border border-dashed border-gray-300 rounded">
                                                                                    <div id="text-center">

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
                                                                        <textarea class="form-control" placeholder="contenu du post" name="content" id="textInput" rows="3"></textarea>
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
                                        <a style="font-weight: bold;" class="text-decoration-none text-dark ml-2" href="">Humeur / Activité</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach (JointurePost($pdo) as $post) : ?>
                    <div class="profile-timeline">
                        <ul class="list-unstyled">
                            <li class="timeline-item">
                                <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);border-radius:10px" class="card  grid-margin">
                                    <div class="card-body">
                                        <div class="content d-flex">
                                            <div class="timeline-item-header">
                                                <a href="profil.php?user_id=<?= $post->user_id ?>"><img src="/images/<?= $post->profil ?>" alt="" /></a>
                                                <p style="font-size: 20px;font-weight:600"><?= $post->username ?></p>
                                            </div>
                                            <?php if ($_SESSION['users']->id == $post->user_id) : ?>
                                                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <small><i style="color: black; font-size: 15px;" class="fa-solid fa-ellipsis"></i></small>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <li>
                                                        <a class="dropdown-item" href="index.php?edit_id=<?= $post->id ?>">
                                                            <i class="fas fa-edit"></i> Modifier la publiaction
                                                        </a>
                                                        <span data-toggle="modal" data-target="#photoModal-1" style="display:none;"></span>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="index.php?delpost=<?= $post->id ?>">
                                                            <i class="fas fa-trash-alt"></i> Supprimer la publication
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php endif ?>
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
                                                    <a href="index.php?post_id=<?= $post->id ?>"><i class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                <?php else : ?>
                                                    <a href="index.php?post_id=<?= $post->id ?>"><i style="color: red;" class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                <?php endif ?>
                                                <a class="editLink" href="#" data-taskid="<?= $post->id ?>" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $post->id ?>" style="z-index: 1050;"><i class="fa fa-comment"></i>Commenter</a>
                                                <div class="modal fade" id="staticBackdrop<?= $post->id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="modal-content">
                                                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="modal-header">
                                                                <h5 style="font-weight: b;" class="modal-title" id="photoModalLabel">Publication de <?= $posts->username ?></h5>
                                                                <a href="index.php">
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
                                                                                        <a href="profil.php?user_id=<?= $posts->user_id ?>"><img src="/images/<?= $posts->profil ?>" alt="" /></a>
                                                                                        <p><?= $post->username ?></p>
                                                                                        <small>3 hours ago</small>
                                                                                    </div>
                                                                                    <div class="timeline-item-post">
                                                                                        <p><?= $post->content ?></p>
                                                                                        <div class="card">
                                                                                            <img src="/images/<?= $posts->picture ?>" alt="">
                                                                                        </div>
                                                                                        <div class="timeline-options d-flex justify-content-around">
                                                                                            <?php if (!checkLikeForUser($pdo, $post->id, $_SESSION['users']->id)) : ?>
                                                                                                <a href="index.php?id=<?= $post->id ?>"><i class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                                                            <?php else : ?>
                                                                                                <a href="index.php?id=<?= $post->id ?>"><i style="color: red;" class="fa fa-thumbs-up"></i> Like (<?= countTableById($post->id, $pdo) ?>)</a>
                                                                                            <?php endif ?>
                                                                                            <a style="cursor: pointer;" class="comment-button"><i class="fa fa-comment"></i>Commenter</a>
                                                                                            <a href="#"><i class="fa fa-share"></i>Partager</a>
                                                                                        </div>
                                                                                        <?php foreach (CommentByPostId($post->id, $pdo) as $comment) : ?>
                                                                                            <div class="timeline-comment d-flex">
                                                                                                <div class="timeline-comment-header">
                                                                                                    <img src="/images/<?= $comment->profil ?>" alt="" />
                                                                                                </div>
                                                                                                <p style="background-color:#EBF2FA; border-radius: 10px; display: inline-block; padding: 10px; color:#000 ; margin-left:5px">
                                                                                                    <span style="font-weight::500;"><?= $comment->username ?></span><br>
                                                                                                    <?= $comment->content ?>
                                                                                                </p>
                                                                                                <?php if ($_SESSION['users']->id == $comment->user_id) : ?>
                                                                                                    <div style="margin-left: 5px;">
                                                                                                        <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                            <i class="fa-solid fa-ellipsis"></i>
                                                                                                        </a>
                                                                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                                                            <li>
                                                                                                                <a class="dropdown-item" href="index.php?edit_comment=<?= $comment->id ?>&id=<?= $posts->id ?>">
                                                                                                                    <i class="fas fa-edit"></i> Modifier le commentaire
                                                                                                                </a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <a class="dropdown-item" href="index.php?delete_comment=<?= $comment->id ?>&id=<?= $post->id ?>">
                                                                                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                                                                                </a>
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                <?php endif ?>
                                                                                            </div>
                                                                                        <?php endforeach ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius:10px" class="modal-footer">
                                                                <?php if (isset($_GET['edit_comment'])) : ?>
                                                                    <form style="margin-top: 20px; width:100%" action="index.php?edit_comment=<?= $edit_comment->id ?>&id=<?= $posts->id ?>" method="post" class="col-12">
                                                                        <div class="comment-box">
                                                                            <img src="/images/<?= $users->profil ?>" class="profile-picture" alt="Image de profil">
                                                                            <div class="comment-input">
                                                                                <textarea name="comments" class="form-control custom-textarea" placeholder="Écrivez un commentaire" onclick="heightTextarea(this)" oninput="autoResize(this)" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"><?= $edit_comment->content ?></textarea>
                                                                                <button type="submit" style="display: block;" name="edit_comment" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                <?php else : ?>
                                                                    <form style="margin-top: 20px; width:100%" action="index.php?id_post=<?= $posts->id ?>&id=<?= $posts->id ?>" method="post" class="col-12">
                                                                        <div class="comment-box">
                                                                            <img src="/images/<?= $users->profil ?>" class="profile-picture" alt="Image de profil">
                                                                            <div class="comment-input">
                                                                                <textarea name="comments" class="form-control custom-textarea" placeholder="Écrivez un commentaire" onclick="heightTextarea(this)" oninput="autoResize(this)" onfocus="addFocusClass(this)" onblur="removeFocusClass(this)"></textarea>
                                                                                <button type="submit" style="display: block;" name="comment" class="send-button"><i class="fas fa-paper-plane"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                <?php endif ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="#"><i class="fa fa-share"></i>Partager</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endforeach ?>
            </div>
            <div style="position: fixed; right:0;" class="col-lg-12 col-xl-3 ">
                <!-- <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0); border-radius:10px" class="card card-white grid-margin">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Suggestions</h4>
                    </div>
                    <div class="card-body">
                        <div class="team">
                            <div class="team-member">
                                <div class="online on"></div>
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="" />
                            </div>
                            <div class="team-member">
                                <div class="online on"></div>
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="" />
                            </div>
                            <div class="team-member">
                                <div class="online off"></div>
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="" />
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0); border-radius:10px;" class="card">
                    <div class="card-body">
                        <h4 class="card-title">Amis connectés</h4>
                        <div class="scrollable-list">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/113948823.png" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 1</h5>
                                            <span class="text-muted">Connecté il y a 5 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 2</h5>
                                            <span class="text-muted">Connecté il y a 10 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 3</h5>
                                            <span class="text-muted">Connecté il y a 15 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 3</h5>
                                            <span class="text-muted">Connecté il y a 15 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 3</h5>
                                            <span class="text-muted">Connecté il y a 15 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 3</h5>
                                            <span class="text-muted">Connecté il y a 15 minutes</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="profile-picture rounded-circle overflow-hidden mr-3">
                                            <img src="images/vieux.jpg" alt="Profile Picture" class="img-fluid">
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Nom de l'ami 3</h5>
                                            <span class="text-muted">Connecté il y a 15 minutes</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <!-- Row -->
    </div>
    <?php require "../Social_network_clone/layouts/footer.php"; ?>