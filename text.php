<div class="profile-timeline">
  <ul class="list-unstyled">
    <li class="timeline-item">
      <div style="border-radius:10px" class="card  grid-margin">
        <div class="card-body">
          <div class="timeline-item-post">
            <div style="margin-bottom: 10px;" class="card">
              <img src="/images/<?= $post_share->picture ?>" alt="">
            </div>
          </div>
          <div class="content d-flex">
            <div class="timeline-item-header">
              <a href="#"><img src="/images/<?= $users_share->profil ?>" alt="" /></a>
              <p style="font-size: 20px;font-weight:600"><?= $users_share->username ?></p>
            </div>
          </div>
          <div class="timeline-item-post">
            <p class="text-dark"><?= $post_share->content ?></p>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>