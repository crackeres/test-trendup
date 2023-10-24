<?php
/* Template Name: Форма для загрузки файла */
get_header();
?>

<div class="container">
    <h2>Форма для загрузки файла</h2>

    <form class="form" method="post" enctype="multipart/form-data" action="<?php echo plugins_url('/trendup/includes/form-handler.php'); ?>">
      <input class="form-control-file" type="file" name="uploaded_file" id="fileInput">
      <input class="button" type="submit" name="upload_file" value="Загрузить файл" id="submitButton" disabled>
    </form>
</div>

<script>
    // Получаем ссылки на элементы формы
    const fileInput = document.getElementById('fileInput');
    const submitButton = document.getElementById('submitButton');

    // Слушаем событие изменения в поле выбора файла
    fileInput.addEventListener('change', function() {
        // Если файл выбран, активируем кнопку отправки
        if (fileInput.files.length > 0) {
            submitButton.removeAttribute('disabled');
        } else {
            // В противном случае, деактивируем кнопку
            submitButton.setAttribute('disabled', 'disabled');
        }
    });
</script>

<?php get_footer(); ?>