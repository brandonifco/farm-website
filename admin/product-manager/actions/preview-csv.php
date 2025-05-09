<?php
/* Generate preview arrays from an uploaded CSV */
if (
    $_FILES['csv']['error'] === UPLOAD_ERR_OK &&
    is_uploaded_file($_FILES['csv']['tmp_name'])
) {
    [$adds, $updates] = previewCsv($_FILES['csv']['tmp_name'], loadProducts());

    $_SESSION['preview_adds']    = $adds;
    $_SESSION['preview_updates'] = $updates;

    header('Location: product-manager.php?preview=1');
    exit;
}

header('Location: product-manager.php?err=csv_upload');
exit;
