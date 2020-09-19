<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <?php if((isset(Auth::user()->id) && !empty(Auth::user()->id))): ?>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            Welcome : <?php echo e(Auth::user()->name); ?>

        </li>
        <li class="nav-item">
            &nbsp; | &nbsp;
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('admin.logout')); ?>"
               onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
                <?php echo e(csrf_field()); ?>

            </form>
        </li>
    </ul>
    <?php endif; ?>
</nav>
<!-- /.navbar -->

