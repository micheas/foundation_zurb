
  <div id="top-bar" class="container">
    <div class="row">
      <?php if ($linked_site_name || $linked_logo): ?>
        <div class="twelve columns">
          <?php if ($is_front): ?>
            <h1 id="site-name"><?php print $linked_site_name; ?></h1>
              <?php if ($linked_logo): ?>
                <?php print $linked_logo; ?>
              <?php endif; ?>
          <?php else: ?>
            <div id="site-name"><?php print $linked_site_name; ?></div>
            <?php if ($linked_logo): ?>
              <?php print $linked_logo; ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="container">
    <div class="row panel">
      <?php if ($main_menu_links): ?>
        <nav class="ten columns">
          <?php print $main_menu_links; ?>
        </nav>
      <?php endif; ?>
      <div class="two columns hide-on-phones">
        <p class="right"><?php print l(t('Login'), 'user/login'); ?> <?php print l(t('Sign Up'), 'user/register', array('attributes' => array('class' => array('small', 'blue', 'nice', 'radius', 'button')))); ?>
      </div>
      <div class="two columns show-on-phones">
        <p><?php print l(t('Login'), 'user/login'); ?> <?php print l(t('Sign Up'), 'user/register'); ?>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div id="main" class="<?php print $main_grid; ?> columns">
        <?php if (!empty($page['highlighted'])): ?>
          <div id="mission">
            <?php print render($page['highlighted']); ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($page['help'])): print render($page['help']); endif; ?>
        <?php if ($title && !$is_front): ?>
          <?php print render($title_prefix); ?>
          <h1 id="page-title" class="title"><?php print $title; ?></h1>
          <?php print render($title_suffix); ?>
        <?php endif; ?>
        <?php if ($messages): print $messages; endif; ?>
        <?php if (!empty($tabs)): ?>
          <div class="tabs">
            <?php print render($tabs); ?>
            <?php if (!empty($tabs2)): print render($tabs2); endif; ?>
          </div>
        <?php endif; ?>
        <?php if ($action_links): ?>
          <ul class="action-links">
            <?php print render($action_links); ?>
          </ul>
        <?php endif; ?>
        <?php print render($page['content']); ?>
      </div>
      <?php if (!empty($page['sidebar_first'])): ?>
        <div id="sidebar-first" class="<?php print $sidebar_first_grid; ?> columns sidebar ">
          <?php print render($page['sidebar_first']); ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($page['sidebar_second'])): ?>
        <div id="sidebar-second" class="three columns sidebar">
          <?php print render($page['sidebar_second']); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <hr />
  <footer class="row">
    <?php if (!empty($page['footer_first'])): ?>
      <div id="footer-first" class="five columns">
        <?php print render($page['footer_first']); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($page['footer_middle'])): ?>
      <div id="footer-middle" class="three columns">
        <?php print render($page['footer_middle']); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($page['footer_last'])): ?>
      <div id="footer-last" class="four columns">
        <?php print render($page['footer_last']); ?>
      </div>
    <?php endif; ?>
  </footer>

  <?php if (!empty($page['bottom'])): ?>
    <div id="bottom-bar" class="tweleve columns">
      &copy; <?php print date('Y') . ' ' . check_plain($site_name) . ' ' . render($page['bottom']); ?>
    </div>
  <?php endif; ?>
