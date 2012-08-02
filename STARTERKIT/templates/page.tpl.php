<div class="top-bar">
  <div class="row">
    <?php if ($linked_site_name || $linked_logo): ?>
      <div class="two columns">
        <?php if ($linked_logo): ?>
          <?php print $linked_logo; ?>
        <?php endif; ?>
      </div>
      <div class="four columns">
        <?php if ($is_front): ?>
          <h1 id="site-name"><?php print $linked_site_name; ?></h1>
        <?php else: ?>
          <div id="site-name"><?php print $linked_site_name; ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if ($main_menu_links): ?>
      <nav class="six columns">
        <?php print $main_menu_links; ?>
      </nav>
    <?php endif; ?>
  </div>
</div>
<?php if ($site_slogan): ?>
  <div class="row">
    <div class="tweleve columns panel radius">
      <?php print $site_slogan; ?>
    </div>
  </div>
<?php endif; ?>
<div class="row">
  <?php if ($messages): print $messages; endif; ?>
  <?php if (!empty($page['help'])): print render($page['help']); endif; ?>
  <div id="main" class="<?php print $main_grid; ?> columns">
    <?php if (!empty($page['highlighted'])): ?>
      <div class="highlight panel callout">
        <?php print render($page['highlighted']); ?>
      </div>
    <?php endif; ?>
    <a id="main-content"></a>
    <?php if ($title && !$is_front): ?>
      <?php print render($title_prefix); ?>
      <h1 id="page-title" class="title"><?php print $title; ?></h1>
      <?php print render($title_suffix); ?>
    <?php endif; ?>

    <?php if (!empty($tabs)): ?>
      <?php print render($tabs); ?>
      <?php if (!empty($tabs2)): print render($tabs2); endif; ?>
    <?php endif; ?>
    <?php if ($action_links): ?>
      <ul class="action-links">
        <?php print render($action_links); ?>
      </ul>
    <?php endif; ?>
    <?php print render($page['content']); ?>
  </div>
  <?php if (!empty($page['sidebar_first'])): ?>
    <div id="sidebar-first" class="<?php print $sidebar_first_grid; ?> columns sidebar">
      <?php print render($page['sidebar_first']); ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($page['sidebar_second'])): ?>
    <div id="sidebar-second" class="<?php print $sidebar_sec_grid; ?> columns sidebar">
      <?php print render($page['sidebar_second']); ?>
    </div>
  <?php endif; ?>
</div>
<?php if (!empty($page['footer_first']) || !empty($page['footer_middle']) || !empty($page['footer_last'])): ?>
  <footer class="row">
    <?php if (!empty($page['footer_first'])): ?>
      <div id="footer-first" class="four columns">
        <?php print render($page['footer_first']); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($page['footer_middle'])): ?>
      <div id="footer-middle" class="four columns">
        <?php print render($page['footer_middle']); ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($page['footer_last'])): ?>
      <div id="footer-last" class="four columns">
        <?php print render($page['footer_last']); ?>
      </div>
    <?php endif; ?>
  </footer>
<?php endif; ?>
<div class="bottom-bar">
  <div class="row">
    <div class="tweleve columns">
      &copy; <?php print date('Y') . ' ' . check_plain($site_name); ?>
    </div>
  </div>
</div>
