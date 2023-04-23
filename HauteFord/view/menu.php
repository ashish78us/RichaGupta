

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
    <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="image/Logo.png" alt="logo" height ="50" padding ="left 20" ><?php echo \app\Helpers\Text::getString(['HauteEcole', 'projet web'], true, 1);?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?view=view/user/Home" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('home');?></a>
                </li>
                <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?view=view/user/About" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('About');?></a>
                </li>
                <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?view=view/user/Blog" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('Blog');?></a>
                </li>
                <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?view=view/user/ContactUs" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('Contact Us');?></a>
                </li>
                <div class="collapse navbar-collapse" id="navbar-menu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="index.php?view=view/user/Register" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('Create Account');?></a>
                </li>
                <?php if (!empty($_SESSION['userid']) ) {?>
                    
                    <li class="nav-item">
                        <a href="index.php?view=api/course/list" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('courses');?></a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?view=api/formation/formationListforUser" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('formation');?></a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?view=api/user/profile/<?=$_SESSION['userid']?>" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('profile');?></a>
                    </li>
                    <?php if(\app\Helpers\Access::isAdmin()) {?>
                     <li class="nav-item">
                        <a href="index.php?view=view/admin/index" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('Admin');?></a>
                    </li>

                        <?php } ?>

                <li class="nav-item">
                        <a href="index.php?view=api/user/logout" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('logout');?></a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a href="index.php?view=view/user/login" class="nav-link"><?php echo \app\Helpers\Text::getStringFromKey('login');?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>


