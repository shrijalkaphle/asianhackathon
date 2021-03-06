<?php error_reporting(0); ?>
<?php
  $title = "Profile";
  include 'layout/header.php';
  $uid = $_GET['id'];

  if(!isset($_SESSION['name'])) {
    $msg = "Cannot view This page! Login to Continue!";
    echo "<script>window.location.replace('login.php?msg=$msg');</script>";
  } 

  $msg = NULL;
  $error = NULL;

  if (isset($_POST['save'])) {
    $msg = NULL;
    $error = NULL;

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $phone = $_POST['phone'];
    $idea = $_POST['idea'];
    $organization = $_POST['organization'];
    $description = $_POST['description'];
    $skill = $_POST['skill'];
    $github = $_POST['github'];
    $linkedin = $_POST['linkedin'];

    $query1 = "UPDATE `user_details` SET `fname`='$fname',`lname`='$lname',`organization`='$organization',`idea`='$idea',`phone`='$phone',`description`='$description',`skill`='$skill',`github`='$github',`linkedin`='$linkedin' WHERE uid = '$uid'";
    $result1 = mysqli_query($conn,$query1) or die ('dberror2');
  }

  if (isset($_POST['pwdupdate'])) {
    $msg = NULL;
    $error = NULL;

    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];

    $query = "SELECT * FROM user WHERE id = '$uid'";
    $result = mysqli_query($conn,$query) or die ('dberror');
    $row = mysqli_fetch_assoc($result);
    $pwd = $row['password'];
    if ($pwd == $pwd1) {
      $query0 = "UPDATE `user` SET `password`='$pwd2'WHERE id = '$uid'";
      $result0 = mysqli_query($conn,$query0);
      $msg = "Password Successfully Updated!";
    } else {
      $error = "Old Password Incorrect!";
    }
  }

  if (isset($_POST['profile'])) {
    $msg = NULL;
    $error = NULL;

    $target = "user_image/";
    $target = $target . basename( $_FILES['photo']['name']);

    $image = ($_FILES['photo']['name']);

    if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        
        $query0 = "SELECT * FROM user_details WHERE uid = '$uid'";
        $result0 = mysqli_query($conn,$query0);
        $row0 = mysqli_fetch_assoc($result0);
        $img = $row0['image'];

        //delete previous user profile picture

        $filename = "user_image" . $img;
        unlink($filename);

        $query1 = "UPDATE user_details SET image = '$image' WHERE uid='$uid' ";
        $result1 = mysqli_query($conn,$query1);
        $msg = "Image Successfully Uploaded!";
    } else {
        $error = "Error while Uploading Image!";
    }
  }

  $query9 = "SELECT * FROM user_details WHERE uid = '$uid'";
  $result9 = mysqli_query($conn,$query9) or die ('dberror3');
  $row = mysqli_fetch_assoc($result9);
  $img = $row['image'];

?>

<?php
  if ($error != NULL) {
?>
    <div class="alert alert-warning fade in alert-dismissible"><center><?php echo $error; ?></center></div>
<?php
  }
?>

<?php
  if ($msg != NULL) {
?>
    <div class="alert alert-success fade in alert-dismissible"><center><?php echo $msg; ?></center></div>
<?php
  }
?>

<br><BR>
<div class="container bootstrap snippet">
    <div class="row">
      <div class="col-sm-10" style="margin-left: 50px"><h1><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></h1></div>
    </div>
    <br><br>
    <div class="row">
      <div class="col-sm-3"><!--left col-->
              

      <div class="text-center">
        <?php
          if ($img == NULL) {
        ?>
            <img src="user_image/default.png" class="avatar img-circle img-thumbnail" alt="avatar">
        <?php
          } else {
        ?>
            <img src="user_image/<?php echo($img) ?>" class="avatar img-circle img-thumbnail" alt="avatar">
        <?php
          }
        ?>
      </div> 
        </div><!--/col-3-->
      <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#view">View Profile</a></li>
                <li><a data-toggle="tab" href="#edit">Edit Profile</a></li>
                <li><a data-toggle="tab" href="#pwd">Change Password/Profile Picture</a></li>
              </ul>

              
          <div class="tab-content">
            <div class="tab-pane active" id="view">
                  

                      <div class="form-group">
                        <div class="col-xs-6">
                            <label for="first_name"><h4>First Name</h4></label>
                            <p class="form-control-static"><?php echo($row['fname'])?></p>
                            <!--<input type="text" class="form-control no-border" name="first_name" id="first_name" value="<?php echo($row['fname'])?>" > -->
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="last_name"><h4>Last Name</h4></label>
                          <p class="form-control-static"><?php echo($row['lname'])?></p>
                          <!--<input type="text" class="form-control no-border" name="last_name" id="last_name" value="<?php echo($row['lname'])?>" disabled> -->
                        </div>
                      </div>
                      
                      <?php if ($_SESSION['role'] != "sponser"): ?>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="phone"><h4>Phone</h4></label>
                          <p class="form-control-static"><?php echo($row['phone'])?></p>
                          <!--<input type="text" class="form-control no-border" name="phone" id="phone" value="<?php echo($row['phone'])?>" disabled> -->
                        </div>
                      </div>

                      <?php endif ?>
                      <?php if ($_SESSION['role'] == "sponser"): ?>
                        <?php if ($row['organization'] != NULL): ?>
                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="phone"><h4>Organization</h4></label>
                          <p class="form-control-static"><?php echo($row['organization'])?></p>
                          <!--<input type="text" class="form-control no-border" name="phone" id="phone" value="<?php echo($row['phone'])?>" disabled> -->
                        </div>
                      </div>
                      <?php endif ?>
                      <?php endif ?>

                      <?php if ($_SESSION['role'] == "entrepneur"): ?>
                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="phone"><h4>Idea</h4></label>
                          <p class="form-control-static"><?php echo($row['idea'])?></p> 
                        </div>
                      </div>
                      <?php endif ?>

                      <?php if ($_SESSION['role'] == "developer"): ?>
                        
                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="skill"><h4>Skills</h4></label>
                          <p class="form-control-static"><?php echo($row['skill'])?></p>
                          <!--<input type="text" class="form-control no-border" name="skill" id="skill" placeholder="<?php echo($row['skill'])?>" disabled> -->
                        </div>
                      </div>

                      <?php endif ?>
                      <?php
                        if ($row['github'] != NULL) {
                      ?>
                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="github"><h4>Github</h4></label>
                          <p class="form-control-static"><a href="<?php echo($row['github'])?>" target="_blank">View Github Profile</a></p>
                          <!--<input type="text" class="form-control no-border" name="github" id="github" value="<?php echo($row['github'])?>" disabled> -->
                        </div>
                      </div>
                      <?php
                        }
                        if ($row['linkedin'] != NULL) {
                      ?>
                      <div class="form-group">    
                        <div class="col-xs-6">
                          <label for="linkedin"><h4>Linkedin Link</h4></label>
                          <p class="form-control-static"><a href="<?php echo($row['linkedin'])?>" target="_blank">View Linkedin Profile</a></p>
                          <!--<input type="text" class="form-control no-border" name="linkedin" id="linkedin" value="<?php echo($row['linkedin'])?>" disabled> -->
                        </div>
                      </div>
                      <?php
                        }
                      ?>

                      <div class="form-group" style="padding-top: 50px">
                        <div class="col-xs-6">
                          <label for="description"><h4>About Me</h4></label>
                          <p class="form-control-static"><?php echo($row['description'])?></p>
                          <!--<input type="text" class="form-control no-border" id="description" value="<?php echo($row['description'])?>" disabled> -->
                        </div>
                      </div>
              
             </div><!--/tab-pane-->
             <div class="tab-pane" id="edit">
              
                  <form class="form" method="post" id="editForm">
                      
                      <div class="form-group">
                        <div class="col-xs-6">
                            <label for="first_name"><h4>First Name</h4></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo($row['fname'])?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="last_name"><h4>Last Name</h4></label>
                          <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo($row['lname'])?>">
                        </div>
                      </div>
                      
                      <?php if ($_SESSION['role'] == "sponser"): ?>

                        <div class="form-group">
                        <div class="col-xs-6">
                          <label for="orginization"><h4>Organization</h4></label>
                          <input type="text" class="form-control" name="organization" id="organization" value="<?php echo($row['organization'])?>">
                        </div>
                      </div>

                    <?php endif ?>
                      <?php if ($_SESSION['role'] != "sponser"): ?>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="phone"><h4>Phone</h4></label>
                          <input type="text" class="form-control" name="phone" id="phone" value="<?php echo($row['phone'])?>">
                        </div>
                      </div>

                      <?php endif ?>
                      <?php if ($_SESSION['role'] == "entrepneur"): ?>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="idea"><h4>Idea</h4></label>
                          <input type="text" class="form-control" name="idea" id="idea" value="<?php echo($row['idea'])?>" required>
                        </div>
                      </div>

                      <?php endif ?>
                      <?php if ($_SESSION['role'] == "developer"): ?>                      

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="skill"><h4>Skills</h4></label>
                          <input type="text" class="form-control" name="skill" id="skill" value="<?php echo($row['skill'])?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="github"><h4>Github Link</h4></label>
                          <input type="text" class="form-control" name="github" id="github" value="<?php echo($row['github'])?>">
                        </div>
                      </div>
                      
                      <div class="form-group">    
                        <div class="col-xs-6">
                          <label for="linkedin"><h4>Linkedin Link</h4></label>
                          <input type="text" class="form-control" name="linkedin" id="linkedin" value="<?php echo($row['linkedin'])?>">
                        </div>
                      </div>

                    <?php endif ?>

                      <div class="form-group">
                        <div class="col-xs-6">
                          <label for="description"><h4>About Me</h4></label>
                          <textarea rows="4" cols="50" class="form-control" name="description" form="editForm"><?php echo($row['description'])?></textarea>
                          <!-- <input type="text" class="form-control" name = "description" id="description" value="<?php echo($row['description'])?>"> -->
                        </div>
                      </div>

                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <button class="btn btn-md" name="save" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <button class="btn btn-md" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                      </div>
                </form>
               
             </div><!--/tab-pane-->
             <div class="tab-pane" id="pwd">
                <form class="form" method="post">

                  <div class="form-group">
                    <div class="col-xs-6">
                        <label for="password"><h4>Old Password</h4></label>
                        <input type="password" class="form-control" name="pwd1" id="password" placeholder="Old Password" title="Enter Your Current Password.">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-xs-6">
                      <label for="password2"><h4>New Password</h4></label>
                      <input type="password" class="form-control" name="pwd2" id="password2" placeholder="New Password" title="Enter Your New Password.">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-xs-12">
                      <br>
                      <button class="btn btn-md" name="pwdupdate" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                    </div>
                  </div>
                </form>
                <h1> </h1>
                <hr><br>
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <div class="col-xs-6">
                      <label for="photo"><h4>Profile Picture</h4></label>
                      <input type="file" class="form-control" name="photo" id="photo">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-xs-12">
                      <br>
                      <button class="btn btn-md" name="profile" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                    </div>
                  </div>
                </form>
              </div>
               
              </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
                                                      