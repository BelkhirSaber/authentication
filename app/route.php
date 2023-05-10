<?php 
  include_once INC_ROUT . '/app/routes/home.php';
  include_once INC_ROUT . '/app/routes/404.php';

  include_once INC_ROUT . '/app/routes/auth/register.php';
  include_once INC_ROUT . '/app/routes/auth/login.php';
  include_once INC_ROUT . '/app/routes/auth/logout.php';
  include_once INC_ROUT . '/app/routes/auth/activate.php';
  include_once INC_ROUT . '/app/routes/auth/password/change.php';
  include_once INC_ROUT . '/app/routes/auth/password/confirm.php';
  include_once INC_ROUT . '/app/routes/auth/password/forget.php';
  include_once INC_ROUT . '/app/routes/auth/password/reset.php';

  include_once INC_ROUT . '/app/routes/user/profile.php';
  include_once INC_ROUT . '/app/routes/user/allUser.php';