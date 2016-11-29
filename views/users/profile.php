<?php
/**
 * @var \FPopov\Core\ViewInterface $this
 * @var \FPopov\Models\View\User\UserProfileViewModel $model
 */
?>

<h1>Welcome <?php echo $model->getUsername()?></h1>

<a href="<?php echo $this->uri('users', 'profileEdit', [$model->getId()])?>">Edit Your Profile</a>
