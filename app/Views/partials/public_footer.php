    <?php if (!empty($footer)): ?>
      <footer class="footer">
        <?= esc($footer) ?>
      </footer>
    <?php endif; ?>
  </div>

  <?php if (!empty($script)): ?>
    <script src="<?= esc($script) ?>"></script>
  <?php endif; ?>
  <?php if (!empty($inlineScript)): ?>
    <script>
      <?= $inlineScript ?>
    </script>
  <?php endif; ?>
</body>
</html>
